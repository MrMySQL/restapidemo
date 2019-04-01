<?php


namespace App\Validation;


use App\Validation\Rule\RuleInterface;

class Validator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var bool
     */
    private $isValid = true;

    /**
     * @var RuleInterface[]
     */
    private $rules;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        $this->errors = [];
        foreach ($this->rules as $rule) {
            if (!$rule->isValid($value)) {
                $this->isValid = false;
                $this->errors[] = $rule->getError();
            }
        }
    }

    /**
     * @var ValidatorInterface
     */
    private static $instance;

    public static function getInstance(): ValidatorInterface
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setRules(array $rules): ValidatorInterface
    {
        $this->rules = $rules;

        return $this;
    }
}