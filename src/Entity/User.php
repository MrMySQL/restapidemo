<?php

namespace App\Entity;

class User
{
    const SESSION_TTL = 60*60*24; //1 day in seconds

    /**
     * @var int
     */
    private $id = null;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $pass = '';

    /**
     * @var string
     */
    private $token = '';

    /**
     * User constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     */
    public function setPass(string $pass): void
    {
        $this->pass = $pass;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return User
     */
    public function setToken(string $token): User
    {
        $this->token = $token;

        return $this;
    }

    public static function fromArray(array $data): User
    {
        //TODO validate

        $user = new self(
            $data['email']
        );

        $user->id = $data['id'];
        $user->pass = $data['password'];

        return $user;
    }
}