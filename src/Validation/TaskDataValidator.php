<?php


namespace App\Validation;


use App\Entity\Task;
use App\Validation\Rule\ContainsFieldsRule;
use App\Validation\Rule\DateTimeConvertableRule;
use App\Validation\Rule\InListRule;
use App\Validation\Rule\NotEmpty;

class TaskDataValidator extends Validator
{
    public function validate($value): bool
    {
        $this->setRules([(new ContainsFieldsRule())->setList(Task::FIELDS_TO_CHECK)]);
        parent::validate($value);

        if ($this->isValid) {
            $this->setRules([new DateTimeConvertableRule()]);
            parent::validate($value[Task::FIELD_DUE]);

            $this->setRules([new NotEmpty()]);
            parent::validate($value[Task::FIELD_TITLE]);

            $this->setRules([(new InListRule())->setList(array_keys(Task::PRIORITY_ALLOWED))]);
            parent::validate($value[Task::FIELD_PRIORITY]);

            return $this->isValid;
        } else {
            return false;
        }
    }
}