<?php

return [
    'namespace' => 'App\\Controller',

    '/' => 'Welcome.index',

    '/{name:string}' => 'Board.index',
    '/{name:string}/post' => [
        'GET' => 'Board.showPostingForm',
        'POST' => 'Board.writePost',
    ],

    '/post/{id:int}' => 'Post.index',
    '/post/{id:int}/edit' => 'Post.showEditForm',
];
