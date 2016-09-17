<?php

use Prob\Handler\ProcInterface;
use App\EventListener\Auth\Validator;
use Core\ViewModel;

return [
    'Controller' => [
        '*.*' => [
            'before' => [
                'App\\EventListener\\Auth\\ValidatorListener.validate',
                'App\\EventListener\\TemplateCommon.putVariable'
            ]
        ],
    ]
];
