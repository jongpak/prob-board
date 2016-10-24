<?php

namespace App\Utils\FormValidator;

use App\Exception\InvalidRequestException;
use Respect\Validation\Validator;

class ContentValidator
{
    public static function subjectValidate($value)
    {
        if(Validator::stringType()->length(1, 255)->validate($value) === false) {
            throw new InvalidRequestException("Length of subject is 1 ~ 255");
        }
    }

    public static function contentValidate($value)
    {
        if(Validator::stringType()->length(1, 65535)->validate($value) === false) {
            throw new InvalidRequestException("Length of content is 1 ~ 65535");
        }
    }
}