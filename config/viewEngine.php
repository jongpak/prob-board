<?php
use App\Utils\Uri\EntityUriFactory;

return [
    'Twig' => [
        'path' => __DIR__ . '/../view/',
        'postfix' => '.twig',
        'settings' => [
            'cache' => __DIR__ . '/../data/twig/cache/',
            'auto_reload' => true
        ]
    ],

    'TwigEx' => [
        'path' => __DIR__ . '/../view/',
        'postfix' => '.twig',
        'settings' => [
            'cache' => __DIR__ . '/../data/twig/cache/',
            'auto_reload' => true
        ],
        'functions' => []
    ]
];
