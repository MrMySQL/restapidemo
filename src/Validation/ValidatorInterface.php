<?php


namespace App\Validation;


use App\Validation\Rule\RuleInterface;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool;

    public function getErrors(): array;

    public function setRules(array $rules): self;
}