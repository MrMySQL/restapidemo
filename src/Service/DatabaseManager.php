<?php

namespace App\Service;

//TODO implement Repository pattern (Doctrine way)
use App\Entity\User;
use App\Model\TaskModel;

class DatabaseManager
{
    const TABLE_NAME_USERS = 'users';
    const TABLE_NAME_TASKS = 'tasks';
    const TABLE_NAME_SESSIONS = 'sessions';

    const TASK_SORT_AVAILABLE = ['title', 'due', 'priority'];

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
        $this->connection = new \PDO($config->getType() . ':host=' . $config->getHost() . ';dbname=' . $config->getDbname(),
            $config->getUsername(), $config->getPassword());
    }

    public static function install(DatabaseConfiguration $config)
    {
        $connection = new \PDO($config->getType() . ':host=' . $config->getHost(),
            $config->getUsername(), $config->getPassword());

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

        $connection->exec('CREATE TABLE `sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL,
  `token` varchar(255) NOT NULL DEFAULT \'\',
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
        return $q->execute([':email' => $email, ':pass' => md5($pass)]);
    }

    public function getUserByToken(string $token)
    {
        $q = $this->connection->prepare(
            'SELECT u.* FROM `users` u LEFT JOIN `sessions` s ON u.id = s.user WHERE s.token = ? AND TIMESTAMPDIFF(SECOND, s.`created`, NOW()) < ?');
        $q->execute([$token, User::SESSION_TTL]);
        if ($data = $q->fetchAll()) {
            return $data;
        } else {
            throw new \Exception('Session expired');
        }
    }

    public function newToken(int $userId): string
    {
        $q = $this->connection->prepare('INSERT INTO `sessions` (`user`, `token`, `created`) VALUES (:userid, :token, NOW())');
        $token = uniqid();

        if ($q->execute([':userid' => $userId, ':token' => $token])) {
            return $token;
        } else {
            throw new \Exception('Session creating failed');
        }
    }

    public function createTask(int $userId, string $title, \DateTimeInterface $due, int $priority)
    {
        $q = $this->connection->prepare('INSERT INTO `tasks` (`user`, `title`, `due`, `priority`) VALUES (:userid, :title, :due, :priority)');
        $r = $q->execute(
            [
                ':userid' => $userId,
                ':title' => $title,
                ':due' => $due->format("Y-m-d H:i:s"),
                ':priority' => $priority
            ]
        );

        if ($r) {
            $q = $this->connection->query('SELECT LAST_INSERT_ID()');
            $q->execute();
            return $q->fetchAll();
        } else {
            throw new \Exception('Task creating failed');
        }
    }

    public function getTaskById(int $id)
    {
        $q = $this->connection->prepare('SELECT * FROM `tasks` WHERE `id` = ?');
        $q->execute([$id]);

        return $q->fetch();
    }

    public function getTasks(int $userId, array $parameters)
    {
        $orderBy = isset($parameters[Request::PARAM_ORDER_BY])
            && in_array(strtolower($parameters[Request::PARAM_ORDER_BY]), self::TASK_SORT_AVAILABLE)
            ? $parameters[Request::PARAM_ORDER_BY] : 'id';

        $orderDirection = isset($parameters[Request::PARAM_ORDER_DIR])
            && in_array(strtolower($parameters[Request::PARAM_ORDER_DIR]), ['asc', 'desc'])
            && $orderBy != 'id'
            ? $parameters[Request::PARAM_ORDER_DIR] : 'asc';

        $page = isset($parameters[Request::PARAM_PAGE_NUMBER]) && is_numeric($parameters[Request::PARAM_PAGE_NUMBER])
            ? $parameters[Request::PARAM_PAGE_NUMBER] : 1;

        $q = $this->connection->prepare('SELECT * FROM `tasks` WHERE `user` = :userid ORDER BY ' . $orderBy . ' ' . $orderDirection . ' LIMIT ' . ($page-1)*TaskModel::ON_PAGE . ', ' . TaskModel::ON_PAGE);

        $q->execute([':userid' => $userId]);

        return $q->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function taskDone(int $id): bool
    {
        return $this->connection->query('UPDATE `tasks` SET `done` = 1 WHERE `id` = ' . $id)->execute();
    }

    public function taskDelete(int $id): bool
    {
        return $this->connection->query('DELETE FROM `tasks` WHERE `id` = ' . $id)->execute();
    }
}