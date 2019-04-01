<?php


namespace App\Validation\Rule;


class InListRule implements RuleInterface
{
    /**
     * @var array
     */
    private $list;

    public function setList(array $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function isValid($value): bool
    {
        return in_array($value, $this->list);
    }

    public function getError(): string
    {
        return 'Unknown attribute';
    }
}