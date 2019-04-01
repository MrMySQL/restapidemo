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
    protected $isValid = true;

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
//        $this->errors = [];
        foreach ($this->rules as $rule) {
            if (!$rule->isValid($value)) {
                $this->isValid = false;
                $this->errors[] = $rule->getError();
            }
        }

        return $this->isValid;
    }

    /**
     * @var ValidatorInterface
     */
    private static $instance;

    public static function getInstance(): ValidatorInterface
    {
        static $instances = array();

        $calledClass = get_called_class();

        if (!isset($instances[$calledClass])) {
            $instances[$calledClass] = new $calledClass();
        }

        return $instances[$calledClass];
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