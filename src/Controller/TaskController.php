<?php


namespace App\Controller;


use App\Entity\Task;
use App\Entity\User;
use App\Model\TaskModel;
use App\Model\UserModel;
use App\Service\DatabaseManager;
use App\Service\Request;
use App\Service\UserMapper;
use App\Validation\Rule\PositiveIntRule;
use App\Validation\TaskDataValidator;
use App\Validation\Validator;

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
            return $this->error(['Auth failed']);
        }

        $body = json_decode($request->getBody(), true);

        if ($body && TaskDataValidator::getInstance()->validate($body)) {
            $title = $body[Task::FIELD_TITLE];
            $due = $body[Task::FIELD_DUE];
            $priority = Task::PRIORITY_ALLOWED[$body[Task::FIELD_PRIORITY]];
        } else {
            return $this->error(TaskDataValidator::getInstance()->getErrors());
        }

        try {
            $due = new \DateTime($due);
            $task = $this->taskModel->new($this->user->getId(), $title, $due, $priority);
            return $task->toArray();
        } catch (\Exception $e) {
            $this->error([$e->getMessage()]);
        }
    }

    private function isAuthorized(Request $request): bool
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
            return $this->error(['Auth failed']);
        }

        $tasks = $this->taskModel->getTasks($this->user->getId(), $request->getParameters());

        foreach ($tasks as $id => $task) {
            $tasks[$id] = $task->toArray();
        }

        return $tasks;
    }

    //TODO DRY
    public function markDoneAction(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return $this->error(['Auth failed']);
        }

        $body = json_decode($request->getBody(), true);

        if (
            $body && isset($body[Task::FIELD_ID])
            && Validator::getInstance()->setRules([new PositiveIntRule()])->validate($body[Task::FIELD_ID])
        ) {
            $id = $body[Task::FIELD_ID];
        } else {
            return $this->error(['No such id']);
        }

        return $this->taskModel->done($id) ? true : $this->error(['Failed']);

    }

    public function deleteAction(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return $this->error(['Auth failed']);
        }

        $body = json_decode($request->getBody(), true);

        if (
            $body && isset($body[Task::FIELD_ID])
            && Validator::getInstance()->setRules([new PositiveIntRule()])->validate($body[Task::FIELD_ID])
        ) {
            $id = $body[Task::FIELD_ID];
        } else {
            return $this->error(['No such id']);
        }

        return $this->taskModel->delete($id) ? true : $this->error(['Failed']);
    }
}