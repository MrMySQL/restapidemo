<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\UserModel;
use App\Service\DatabaseManager;
use App\Service\Request;
use App\Validation\UserDataValidator;

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

    //TODO DRY
    public function signUpAction(Request $request)
    {
        $body = json_decode($request->getBody(), true);

        if ($body && UserDataValidator::getInstance()->validate($body)) {
            $email = $body[User::FIELD_EMAIL];
            $pass = $body[User::FIELD_PASSWORD];
        } else {
            return $this->error(UserDataValidator::getInstance()->getErrors());
        }

        try {
            $token = $this->userModel->signUp($email, $pass);
            return $token;
        } catch (\Exception $e) {
            return $this->error([$e->getMessage()]);
        }
    }

    public function signInAction(Request $request)
    {
        $body = json_decode($request->getBody(), true);

        if ($body && UserDataValidator::getInstance()->validate($body)) {
            $email = $body[User::FIELD_EMAIL];
            $pass = $body[User::FIELD_PASSWORD];
        } else {
            return $this->error(UserDataValidator::getInstance()->getErrors());
        }

        try {
            $token = $this->userModel->signIn($email, $pass);
            return $token;
        } catch (\Exception $e) {
            return $this->error([$e->getMessage()]);
        }
    }
}