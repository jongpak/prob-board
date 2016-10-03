<?php

return [
    'namespace' => 'App\\Controller',

    '/' => 'Welcome.index',

    '/auth/login' => [
        'GET' => 'Auth.viewLoginForm',
        'POST' => 'Auth.doLogin'
    ],
    '/auth/logout' => 'Auth.doLogout',

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
    '/post/{id:int}/delete' => [
        'GET' => 'Post.showDeleteForm',
        'POST' => 'Post.delete'
    ],
    '/post/{id:int}/comment' => [
        'POST' => 'Post.writeComment'
    ],

    '/comment/{id:int}/edit' => [
        'GET' => 'Comment.showEditForm',
        'POST' => 'Comment.edit'
    ],
    '/comment/{id:int}/delete' => [
        'GET' => 'Comment.showDeleteForm',
        'POST' => 'Comment.delete'
    ],

    '/file/{id:int}' => 'Attachment.index'
];
