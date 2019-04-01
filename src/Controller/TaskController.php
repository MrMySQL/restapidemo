<?php


namespace App\Controller;


use App\Entity\User;
use App\Model\TaskModel;
use App\Model\UserModel;
use App\Service\DatabaseManager;
use App\Service\Request;
use App\Service\UserMapper;

class TaskController extends AbstractController
{
    /**
     * @var TaskModel
     */
    private $taskModel;

    /**
     * @var User
     */
    private $user;

    /**
     * UserController constructor.
     * @param DatabaseManager $databaseManager
     * @param TaskModel $taskModel
     */
    public function __construct(DatabaseManager $databaseManager, TaskModel $taskModel)
    {
        parent::__construct($databaseManager);
        $this->taskModel = $taskModel;
    }

    public function newAction(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return 'Auth failed';
        }

        //TODO implement Validator
        if ($body = json_decode($request->getBody())) {
            $title = $body->title;
            $due = $body->due;
            $priority = $body->priority;
        } else {
            //TODO return unified error
        }

        try {
            $due = new \DateTime($due);
            $task = $this->taskModel->new($this->user->getId(), $title, $due, $priority);
            return $task->toArray();
        } catch (\Exception $e) {
            //TODO return unified error
        }
    }

    private function isAuthorized(Request $request)
    {
        $userModel = new UserModel(new UserMapper($this->databaseManager));

        try {
            $this->user = $userModel->authorize($request->getToken());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getTasksAction(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return 'Auth failed';
        }

        $tasks = $this->taskModel->getTasks($this->user->getId(), $request->getParameters());

        foreach ($tasks as $id => $task) {
            $tasks[$id] = $task->toArray();
        }

        return $tasks;
    }

    public function markDoneAction(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return 'Auth failed';
        }

        //TODO implement Validator
        if ($body = json_decode($request->getBody())) {
            $id = $body->id;
        } else {
            //TODO return unified error
        }

        return $this->taskModel->done($id);

    }

    public function deleteAction(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return 'Auth failed';
        }

        //TODO implement Validator
        if ($body = json_decode($request->getBody())) {
            $id = $body->id;
        } else {
            //TODO return unified error
        }

        return $this->taskModel->delete($id);
    }
}