<?php


namespace App\Validation\Rule;


class PositiveIntRule implements RuleInterface
{

    public function isValid($value): bool
    {
        return is_int($value) && $value > 0;
    }

    public function getError(): string
    {
        return 'Value is not positive integer';
    }
}