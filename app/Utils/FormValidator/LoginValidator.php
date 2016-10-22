<?php

namespace App\Utils\FormValidator;

use App\Exception\InvalidRequestException;
use Respect\Validation\Validator;

class LoginValidator
{
    public static function accountIdValidate($value)
    {
        if(Validator::stringType()->length(6, 128)->validate($value)) {
            throw new InvalidRequestException("Length of account id is 4 ~ 128");
        }
    }

    public static function passwordValidate($value)
    {
        if(Validator::stringType()->length(6, 128)->validate($value)) {
            throw new InvalidRequestException("Length of account password is 4 ~ 128");
        }
    }
}