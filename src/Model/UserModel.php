<?php

namespace App\Model;

use App\Entity\User;
use App\Service\UserMapper;

class UserModel extends AbstractModel
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var UserMapper
     */
    private $dataMapper;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserModel
     */
    public function setUser(User $user): UserModel
    {
        $this->user = $user;

        return $this;
    }

    public function signUp(string $email, string $pass): string
    {
        if ($this->dataMapper->findByEmail($email)) {
            throw new \Exception('User already exists');
        }

        $this->user = $this->dataMapper->createUser($email, $pass);

        return $this->dataMapper->getToken($this->user->getId());
    }

    public function signIn(string $email, string $pass)
    {
        if ($this->dataMapper->findByEmail($email) and $this->user->getPass() == md5($pass)) {
            return $this->dataMapper->getToken($this->user->getId());
        } else {
            throw new \Exception('Authentication failed');
        }
    }

    /**
     * @param UserMapper $dataMapper
     */
    public function setDataMapper(UserMapper $dataMapper): void
    {
        $this->dataMapper = $dataMapper;
    }
}