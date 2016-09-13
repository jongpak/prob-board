<?php

use Prob\Handler\ProcInterface;
use App\EventListener\Auth\Validator;

return [
    'Controller' => [
        '*.*' => [
            'before' => 'App\\EventListener\\Auth\\ValidatorListener.validate'
        ],
    ]
];
