<?php

namespace App\Model;

use App\Entity\User;
use App\Service\UserMapper;

class UserModel
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
     * UserModel constructor.
     * @param UserMapper $dataMapper
     */
    public function __construct(UserMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

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
        try {
            $this->user = $this->dataMapper->findByEmail($email);

            throw new \Exception('User already exists.');
        } catch (\Exception $e) {
            //User does not exists. It's ok.
        }

        $this->user = $this->dataMapper->createUser($email, $pass);

        return $this->dataMapper->getToken($this->user->getId());
    }

    public function signIn(string $email, string $pass): string
    {
        $this->user = $this->dataMapper->findByEmail($email);

        if ($this->user && $this->user->getPass() == md5($pass)) {
            return $this->dataMapper->getToken($this->user->getId());
        } else {
            throw new \Exception('Authentication failed');
        }
    }

    public function authorize(string $token): User
    {
        $this->user = $this->dataMapper->findByToken($token);

        return $this->user;
    }
}