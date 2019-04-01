<?php


namespace App\Validation\Rule;


interface RuleInterface
{
    public function isValid($value): bool;

    public function getError(): string;
}