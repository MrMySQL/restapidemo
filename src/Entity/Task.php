<?php


namespace App\Entity;


class Task
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTimeInterface
     */
    private $due;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var bool
     */
    private $done;

    /**
     * Task constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDue(): \DateTimeInterface
    {
        return $this->due;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }

    public static function fromArray(array $data): Task
    {
        //TODO validate

        $task = new self(
            $data['title']
        );

        $task->id = $data['id'];
        $task->due = $data['due'];
        $task->priority = $data['priority'];
        $task->done = $data['done'];

        return $task;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'due' => $this->due,
            'priority' => $this->priority,
            'done' => $this->done,
        ];
    }
}