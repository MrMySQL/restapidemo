<?php


namespace App\Validation\Rule;


class EmailRule implements RuleInterface
{

    public function isValid($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function getError(): string
    {
        return 'Value is not valid email';
    }
}