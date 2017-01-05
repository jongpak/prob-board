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
        'functions' => [
            'createUri' => function ($entity, $parameters = []) {
                return EntityUriFactory::getEntityUri($entity)->create($parameters);
            },
            'readUri' => function ($entity, $parameters = []) {
                return EntityUriFactory::getEntityUri($entity)->read($parameters);
            },
            'updateUri' => function ($entity, $parameters = []) {
                return EntityUriFactory::getEntityUri($entity)->update($parameters);
            },
            'deleteUri' => function ($entity, $parameters = []) {
                return EntityUriFactory::getEntityUri($entity)->delete($parameters);
            },
            'readyEditor' => function($id) {
                return '<script>' .
                        'var simplemde = new SimpleMDE({' .
                        'element: document.getElementById("' . $id . '"),' .
                        'spellChecker: false' .
                        ' });</script>';
            }
        ]
    ]
];
