<?php

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
