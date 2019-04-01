<?php


namespace App\Validation;


use App\Validation\Rule\ContainsFieldsRule;
use App\Validation\Rule\DateTimeConvertableRule;
use App\Validation\Rule\InListRule;

class TaskDataValidator extends Validator
{
    public function validate($value): bool
    {
        return $this->setRules([(new ContainsFieldsRule())->setList(['title', 'due', 'priority'])])->validate($value)
            && $this->setRules([new DateTimeConvertableRule()])->validate($value['due'])
            && $this->setRules([(new InListRule())->setList(['low', 'normal', 'high'])])->validate($value['priority']);
    }
}