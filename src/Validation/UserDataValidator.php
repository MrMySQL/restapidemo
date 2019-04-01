<?php


namespace App\Validation;


use App\Validation\Rule\ContainsFieldsRule;
use App\Validation\Rule\EmailRule;

class UserDataValidator extends Validator
{
    public function validate($value): bool
    {
        return $this->setRules([(new ContainsFieldsRule())->setList(['email', 'pass'])])->validate($value)
        && $this->setRules([new EmailRule()])->validate($value['email']);
    }
}