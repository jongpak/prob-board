<?php

use App\Auth\Exception\AccountNotFound;
use App\EventListener\Auth\Exception\PermissionDenied;
use App\Exception\EntityNotFound;

return [
    'displayErrors' => true,
    'enableReporters' => [ 'Html' ],

    'reporters' => [
        'Html' => [
            'class' => 'App\\ErrorReporter\\Html',
            'view' => 'App\\ViewEngine\\TwigView',
            'path' => __DIR__ . '/../view/error/',
            'file' => 'exception',
            'postfix' => '.twig',

            'displayExceptionInfo' => true,
            'displayFileInfo' => true,
            'displayStackTrace' => true,
            'displayErrorSourceLines' => true,

            'settings' => []
        ]
    ],

    'errorCodes' => [
        AccountNotFound::class => 403,
        PermissionDenied::class => 403,
        EntityNotFound::class => 404
    ]
];
