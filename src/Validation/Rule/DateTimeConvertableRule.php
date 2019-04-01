<?php


namespace App\Validation\Rule;


class DateTimeConvertableRule implements RuleInterface
{

    public function isValid($value): bool
    {
        try {
            new \DateTime($value);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function getError(): string
    {
        return 'Invalid datetime format';
    }
}