<?php

return [
    'Board.write' => [
        'subject' => 'App\\Utils\\FormValidator\\ContentValidator.subjectValidate',
        'content' => 'App\\Utils\\FormValidator\\ContentValidator.contentValidate',
        'author' => 'App\\Utils\\FormValidator\\UserContentValidator.authorValidate',
        'password' => 'App\\Utils\\FormValidator\\ContentValidator.passwordValidate',
    ]
];