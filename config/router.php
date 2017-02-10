<?php

return [
    'namespace' => 'App\\Controller',

    '/' => 'Welcome.index',

    '/auth/login' => [
        'GET' => 'Auth.viewLoginForm',
        'POST' => 'Auth.login'
    ],
    '/auth/logout' => 'Auth.logout',
    '/auth/register' => [
        'GET' => 'Auth.viewRegisterForm',
        'POST' => 'Auth.register'
    ],

    '/board/{name:string}' => 'Board.index',
    '/board/{name:string}/create' => [
        'GET' => 'Board.showPostingForm',
        'POST' => 'Board.writePost',
    ],

    '/post/{id:int}' => 'Post.index',
    '/post/{id:int}/confirm' => [
        'POST' => 'Post.editConfirm'
    ],
    '/post/{id:int}/edit' => [
        'GET' => 'Post.showEditForm',
        'POST' => 'Post.edit'
    ],
    '/post/{id:int}/delete' => [
        'GET' => 'Post.showDeleteForm',
        'POST' => 'Post.delete'
    ],
    '/post/{id:int}/create' => [
        'POST' => 'Post.writeComment'
    ],

    '/comment/{id:int}/confirm' => [
        'POST' => 'Comment.editConfirm'
    ],
    '/comment/{id:int}/edit' => [
        'GET' => 'Comment.showEditForm',
        'POST' => 'Comment.edit'
    ],
    '/comment/{id:int}/delete' => [
        'GET' => 'Comment.showDeleteForm',
        'POST' => 'Comment.delete'
    ],

    '/attachmentfile/{id:int}' => 'Attachment.index',

    '/admin' => 'Admin\\Welcome.index',
    '/admin/route' => 'Admin\\Welcome.viewRoutePaths',
    '/admin/event' => 'Admin\\Welcome.viewEvents',
];
