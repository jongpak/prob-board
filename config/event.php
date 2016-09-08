<?php

use Prob\Handler\ProcInterface;
use App\EventListener\Auth\Validator;

return [
    'Controller' => [
        '*.*' => [
            'before' => function (ProcInterface $proc) {
                $validator = new Validator(require '../app/EventListener/Auth/config/controllerPermission.php');
                $validator->validate($proc);
            }
        ],
    ]
];
