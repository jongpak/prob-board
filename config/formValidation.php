<?php

use Respect\Validation\Validator;
use App\Auth\LoginManagerInterface;
use App\Exception\InvalidRequestException;

return [
    'Board.write' => [
        'subject' => function ($value) {
            if(Validator::stringType()->length(1, 255)->validate($value) == false) {
                throw new InvalidRequestException("Length of subject is 1 ~ 255");
            }
        },
        'content' => function ($value) {
            if(Validator::stringType()->length(1, 65535)->validate($value) == false) {
                throw new InvalidRequestException("Length of content is 1 ~ 65535");
            }
        },
        'author' => function ($value, LoginManagerInterface $loginManager) {
            if($loginManager->isLogged() === false && empty($value)) {
                throw new InvalidRequestException("Author name is required");
            }
        },
        'password' => function ($value, LoginManagerInterface $loginManager) {
            if($loginManager->isLogged() === false && empty($value)) {
                throw new InvalidRequestException("Password is required");
            }
        }
    ]
];