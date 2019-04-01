<?php


namespace App\Service;


use App\Entity\User;

class UserMapper extends DataMapper
{
    /**
     * @param array $row
     * @return User
     */
    private function mapRowToUser(array $row): User
    {
        return User::fromArray($row);
    }

    /**
     * @param string $email
     * @return User
     * @throws \Exception
     */
    public function findByEmail(string $email): User
    {
        $result = $this->databaseManager->findUserByEmail($email);

        if (empty($result)) {
            throw new \Exception("User not found");
        }

        return $this->mapRowToUser($result[0]);
    }

    public function findByToken(string $token): User
    {
        $result = $this->databaseManager->getUserByToken($token);

        if (empty($result)) {
            throw new \Exception("User not found");
        }

        return $this->mapRowToUser($result[0]);
    }

    /**
     * @param string $email
     * @param string $pass
     * @return User
     * @throws \Exception
     */
    public function createUser(string $email, string $pass): User
    {
        $result = $this->databaseManager->createUser($email, $pass);

        if ($result) {
            return $this->findByEmail($email);
        } else {
            throw new \Exception('Could not create user');
        }
    }

    public function getToken(int $userId): string
    {
        return $this->databaseManager->newToken($userId);
    }
}