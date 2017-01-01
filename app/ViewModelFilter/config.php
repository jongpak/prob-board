<?php

return [
    'enables' => [ 'MarkdownFilter',  ],

    'filters' => [
        'EscapeFilter' => [
            'class' => 'App\\ViewModelFilter\\Filter\\EscapeFilter',
            'settings' => []
        ],

        'MarkdownFilter' => [
            'class' => 'App\\ViewModelFilter\\Filter\\MarkdownFilter',
            'settings' => []
        ]
    ]
];