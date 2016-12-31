<?php

return [
    'applyView' => [
        'App\\ViewEngine\\TwigViewEx',
    ],

    'default' => 'default/',

    'controller' => [
        'Auth.*' => 'auth/',
        'Admin\\*' => 'admin/',
    ],
];