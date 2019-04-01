<?php


namespace App\Validation\Rule;


class NotEmpty implements RuleInterface
{

    public function isValid($value): bool
    {
        return is_string($value) && strlen($value) > 0;
    }

    public function getError(): string
    {
        return 'Empty value';
    }
}