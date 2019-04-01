<?php


namespace App\Validation\Rule;


class ContainsFieldsRule implements RuleInterface
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
        $array_keys = array_keys($value);
        sort($array_keys);
        sort($this->list);
        return is_array($value) && $array_keys == $this->list;
    }

    public function getError(): string
    {
        return 'Missing required field';
    }
}