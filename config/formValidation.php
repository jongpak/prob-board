<?php

use App\Exception\InvalidRequestException;
use App\Utils\FormValidator\LoginValidator;
use App\Utils\FormValidator\RegisterValidator;

return [
    'Auth.login' => [
        'account_id' => 'App\\Utils\\FormValidator\\LoginValidator.accountIdValidate',
        'password' => 'App\\Utils\\FormValidator\\LoginValidator.passwordValidate',
    ],
    'Auth.register' => [
        'account_id' => [
            'App\\Utils\\FormValidator\\LoginValidator.accountIdValidate',
            'App\\Utils\\FormValidator\\RegisterValidator.accountIdDuplicateValidate',
        ],
        'password' => 'App\\Utils\\FormValidator\\LoginValidator.passwordValidate',
        'password-confirm' => [
            function($value) {
                try {
                    LoginValidator::passwordValidate($value);
                } catch(InvalidRequestException $e) {
                    throw new InvalidRequestException('Please, enter confirmation of password');
                }
            },
            function ($value, $parsedBody) {
                RegisterValidator::passwordConfirmValidate($value, $parsedBody['password']);
            }
        ],
        'nickname' => 'App\\Utils\\FormValidator\\RegisterValidator.nicknameValidate',
        'email' => 'App\\Utils\\FormValidator\\RegisterValidator.emailValidate',
    ],
    'Board.writePost' => [
        'subject' => 'App\\Utils\\FormValidator\\ContentValidator.subjectValidate',
        'content' => 'App\\Utils\\FormValidator\\ContentValidator.contentValidate',
        'author' => 'App\\Utils\\FormValidator\\UserContentValidator.authorValidate',
        'password' => 'App\\Utils\\FormValidator\\UserContentValidator.passwordValidate',
    ],
    'Post.writeComment' => [
        'content' => 'App\\Utils\\FormValidator\\ContentValidator.contentValidate',
        'author' => 'App\\Utils\\FormValidator\\UserContentValidator.authorValidate',
        'password' => 'App\\Utils\\FormValidator\\UserContentValidator.passwordValidate',
    ],
    'Post.edit' => [
        'subject' => 'App\\Utils\\FormValidator\\ContentValidator.subjectValidate',
        'content' => 'App\\Utils\\FormValidator\\ContentValidator.contentValidate',
        'author' => 'App\\Utils\\FormValidator\\UserContentValidator.authorValidate',
        'password' => 'App\\Utils\\FormValidator\\UserContentValidator.passwordValidate',
    ],
    'Comment.edit' => [
        'content' => 'App\\Utils\\FormValidator\\ContentValidator.contentValidate',
        'author' => 'App\\Utils\\FormValidator\\UserContentValidator.authorValidate',
        'password' => 'App\\Utils\\FormValidator\\UserContentValidator.passwordValidate',
    ],
    'Post.delete' => [
        'password' => 'App\\Utils\\FormValidator\\UserContentValidator.passwordValidate',
    ],
    'Comment.delete' => [
        'password' => 'App\\Utils\\FormValidator\\UserContentValidator.passwordValidate',
    ],
];