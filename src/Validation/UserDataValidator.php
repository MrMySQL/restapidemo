<?php


namespace App\Validation;


use App\Entity\User;
use App\Validation\Rule\ContainsFieldsRule;
use App\Validation\Rule\EmailRule;
use App\Validation\Rule\NotEmpty;

class UserDataValidator extends Validator
{
    public function validate($value): bool
    {
        $this->setRules([(new ContainsFieldsRule())->setList(User::FIELDS_TO_CHECK)]);
        parent::validate($value);

        if ($this->isValid) {
            $this->setRules([new EmailRule()]);
            parent::validate($value[User::FIELD_EMAIL]);

            $this->setRules([new NotEmpty()]);
            parent::validate($value[User::FIELD_PASSWORD]);

            return $this->isValid;
        } else {
            return false;
        }
    }
}