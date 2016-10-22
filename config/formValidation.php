<?php

return [
    'Auth.login' => [
        'id' => 'App\\Utils\\FormValidator\\LoginValidator.accountIdValidate',
        'password' => 'App\\Utils\\FormValidator\\LoginValidator.passwordValidate',
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