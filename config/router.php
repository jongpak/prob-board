<?php

return [
    'namespace' => 'App\\Controller',

    '/' => 'Welcome.index',

    '/{name:string}' => 'Board.index',
    '/{name:string}/post' => [
        'GET' => 'Board.showPostingForm',
        'POST' => 'Board.write',
    ],

    '/post/{id:int}' => 'Post.index',
    '/post/{id:int}/edit' => [
        'GET' => 'Post.showEditForm',
        'POST' => 'Post.edit'
    ],
];
