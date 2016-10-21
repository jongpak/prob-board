<?php

namespace App\Utils\FormValidator;

use App\Auth\LoginManagerInterface;
use App\Exception\InvalidRequestException;

class UserContentValidator
{
    public static function authorValidate($value, LoginManagerInterface $loginManager) {
        if($loginManager->isLogged() === false && empty($value)) {
            throw new InvalidRequestException("Author name is required");
        }
    }

    public static function passwordValidate($value, LoginManagerInterface $loginManager) {
        if($loginManager->isLogged() === false && empty($value)) {
            throw new InvalidRequestException("Password is required");
        }
    }
}