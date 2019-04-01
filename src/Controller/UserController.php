<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Service\DatabaseManager;
use App\Service\Request;

class UserController extends AbstractController
{
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * UserController constructor.
     * @param DatabaseManager $databaseManager
     * @param UserModel $taskModel
     */
    public function __construct(DatabaseManager $databaseManager, UserModel $taskModel)
    {
        parent::__construct($databaseManager);
        $this->userModel = $taskModel;
    }


    public function signUpAction(Request $request)
    {
        //TODO implement Validator
        if ($body = json_decode($request->getBody())) {
            $email = $body->email;
            $pass = $body->pass;
        } else {
            //TODO return unified error
        }

        try {
            $token = $this->userModel->signUp($email, $pass);
            return $token;
        } catch (\Exception $e) {
            //TODO return unified error
        }
    }

    public function signInAction(Request $request)
    {
        //TODO implement Validator
        if ($body = json_decode($request->getBody())) {
            $email = $body->email;
            $pass = $body->pass;
        } else {
            //TODO return unified error
        }

        try {
            $token = $this->userModel->signIn($email, $pass);
            return $token;
        } catch (\Exception $e) {
            //TODO return unified error
        }
    }
}