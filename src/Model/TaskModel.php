<?php


namespace App\Model;


use App\Entity\Task;
use App\Service\TaskMapper;

class TaskModel
{
    const ON_PAGE = 5;

    /**
     * @var Task
     */
    private $task;

    /**
     * @var TaskMapper
     */
    private $dataMapper;

    /**
     * UserModel constructor.
     * @param TaskMapper $dataMapper
     */
    public function __construct(TaskMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function new(int $userId, string $title, \DateTimeInterface $due, int $priority): Task
    {
            $this->task = $this->dataMapper->createTask($userId, $title, $due, $priority);
            return $this->task;
    }

    /**
     * @param int $userId
     * @param array $parameters
     * @return Task[]
     */
    public function getTasks(int $userId, array $parameters): array
    {
        return $this->dataMapper->getTasks($userId, $parameters);
    }

    public function done(int $id): bool
    {
        return $this->dataMapper->done($id);
    }

    public function delete(int $id): bool
    {
        return $this->dataMapper->delete($id);
    }
}