<?php

namespace App\Utils\FormValidator;

use App\Entity\User;
use App\Exception\InvalidRequestException;
use Core\Utils\EntityFinder;
use Respect\Validation\Validator;

class RegisterValidator
{
    public static function accountIdDuplicateValidate($value)
    {
        if(EntityFinder::findOneBy(User::class, ['accountId' => $value]) !== null) {
            throw new InvalidRequestException(sprintf('The [%s] account ID already exists', $value));
        }
    }

    public static function passwordConfirmValidate($value, $confirmValue)
    {
        if($value !== $confirmValue) {
            throw new InvalidRequestException('Password not equal to password of confirmation');
        }
    }

    public static function nicknameValidate($value)
    {
        if(Validator::stringType()->length(1, 16)->validate($value) === false) {
            throw new InvalidRequestException('Length of nickname is 1 ~ 16');
        }
    }

    public static function emailValidate($value)
    {
        if(Validator::email()->validate($value) === false) {
            throw new InvalidRequestException('The email address is incorrect');
        }
    }
}