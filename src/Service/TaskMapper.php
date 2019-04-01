<?php


namespace App\Service;

use App\Entity\Task;

class TaskMapper extends DataMapper
{
    /**
     * @param array $row
     * @return Task
     */
    private function mapRowToTask(array $row): Task
    {
        return Task::fromArray($row);
    }

    public function createTask(int $userId, string $title, \DateTimeInterface $due, int $priority): Task
    {
        $result = $this->databaseManager->createTask($userId, $title, $due, $priority);
        $taskData = $this->databaseManager->getTaskById($result[0][0]);

        if (!empty($taskData)) {
            return $this->mapRowToTask($taskData);
        } else {
            throw new \Exception('Task creating failed');
        }
    }

    /**
     * @param int $userId
     * @param array $parameters
     * @return Task[]
     */
    public function getTasks(int $userId, array $parameters): array
    {
        $data = $this->databaseManager->getTasks($userId, $parameters);
        $tasks = [];

        foreach ($data as $row) {
            $tasks[] = $this->mapRowToTask($row);
        }

        return $tasks;
    }

    public function done(int $id): bool
    {
        return $this->databaseManager->taskDone($id);
    }

    public function delete(int $id): bool
    {
        return $this->databaseManager->taskDelete($id);
    }
}