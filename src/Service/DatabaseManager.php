<?php

namespace App\Service;

//TODO implement Repository pattern (Doctrine way)
use App\Entity\User;

class DatabaseManager
{
    const TABLE_NAME_USERS = 'users';
    const TABLE_NAME_TASKS = 'tasks';
    const TABLE_NAME_SESSIONS = 'sessions';

    /**
     * @var DatabaseConfiguration
     */
    private $configuration;

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @param DatabaseConfiguration $config
     */
    public function __construct(DatabaseConfiguration $config)
    {
        $this->configuration = $config;
        $this->connection = new \PDO('mysql:host=' . $config->getHost() . ';dbname=' . $config->getDbname(),
            $config->getUsername(), $config->getPassword());
    }

    public static function install(DatabaseConfiguration $config)
    {
        $connection = new \PDO('mysql:host=' . $config->getHost(),
            $config->getUsername(), $config->getPassword());;

        try {
            self::createDatabase($connection, $config->getDbname());
            self::createSchema($connection);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @param \PDO $connection
     * @param string $dbname
     * @throws \Exception
     */
    private static function createDatabase(\PDO $connection, string $dbname): void
    {
        if (!$connection->exec('CREATE DATABASE IF NOT EXISTS ' . $dbname)) {
            throw new \Exception('Could not create database');
        }
    }

    private static function createSchema(\PDO $connection): void
    {
        $connection->exec('CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT \'\' COMMENT \'Email\',
  `password` varchar(64) NOT NULL DEFAULT \'\',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

        $connection->exec('CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT \'\',
  `due` datetime NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_tasks` (`user`),
  CONSTRAINT `user_tasks` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

        $connection->exec('CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL,
  `token` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
    }

    public function findUserById(int $id): array
    {
        $q = $this->connection->prepare('SELECT * FROM `users` WHERE `id` = ?');
        $q->execute([$id]);

        return $q->fetchAll();
    }

    public function findUserByEmail(string $email): array
    {
        $q = $this->connection->prepare('SELECT * FROM `users` WHERE `email` = ?');
        $q->execute([$email]);

        return $q->fetchAll();
    }

    public function createUser(string $email, string $pass): bool
    {
        $q = $this->connection->prepare('INSERT INTO `users` (`email`, `password`) VALUES (:email, :pass)');
        return $q->execute(['email' => $email, 'pass' => md5($pass)]);
    }

    public function getUserByToken(string $token)
    {
        $q = $this->connection->prepare(
            'SELECT `user` FROM `sessions` WHERE `token` = ? AND TIMESTAMPDIFF(SECOND, `created`, NOW()) < ?');
        $q->execute([$token, User::SESSION_TTL]);
        if ($data = $q->fetchAll()) {
            return $this->findUserById($data['user']);
        } else {
            throw new \Exception('Session expired');
        }
    }

    public function newToken(int $userId): string
    {
        $q = $this->connection->prepare('INSERT INTO `sessions` (`user`, `token`, `created`) VALUES (:user, :token, NOW())');
        $token = uniqid();

        if ($q->execute(['user' => $userId, 'token' => $token])) {
            return $token;
        } else {
            throw new \Exception('Session creating failed');
        }
    }
}