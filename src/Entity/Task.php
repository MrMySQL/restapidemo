<?php


namespace App\Entity;


class Task
{
    const FIELD_ID = 'id';
    const FIELD_TITLE = 'title';
    const FIELD_DUE = 'due';
    const FIELD_PRIORITY = 'priority';
    const FIELD_DONE = 'done';
    const FIELDS_TO_CHECK = [self::FIELD_TITLE, self::FIELD_DUE, self::FIELD_PRIORITY];

    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';

    const PRIORITY_ALLOWED = [
        self::PRIORITY_LOW => 1,
        self::PRIORITY_NORMAL => 2,
        self::PRIORITY_HIGH => 3
    ];

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
            $data[self::FIELD_TITLE]
        );

        $task->id = $data[self::FIELD_ID];
        $task->due = $data[self::FIELD_DUE];
        $task->priority = $data[self::FIELD_PRIORITY];
        $task->done = $data[self::FIELD_DONE];

        return $task;
    }

    public function toArray()
    {
        return [
            self::FIELD_ID => $this->id,
            self::FIELD_TITLE => $this->title,
            self::FIELD_DUE => $this->due,
            self::FIELD_PRIORITY => array_search($this->priority, self::PRIORITY_ALLOWED),
            self::FIELD_DONE => $this->done,
        ];
    }
}