<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Service\DatabaseManager;

class UserController extends AbstractController
{
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * UserController constructor.
     * @param DatabaseManager $databaseManager
     * @param UserModel $userModel
     */
    public function __construct(DatabaseManager $databaseManager, UserModel $userModel)
    {
        parent::__construct($databaseManager);
        $this->userModel = $userModel;
    }


    public function signUpAction(string $email, string $pass)
    {
        try {
            $token = $this->userModel->signUp($email, $pass);
            return $token;
        } catch (\Exception $e) {
            //TODO return unified error
        }
    }

    public function singInAction(string $email, string $pass)
    {
        try {
            $token = $this->userModel->signIn($email, $pass);
            return $token;
        } catch (\Exception $e) {
            //TODO return unified error
        }
    }
}