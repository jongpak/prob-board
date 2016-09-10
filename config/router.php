<?php

return [
    'namespace' => 'App\\Controller',

    '/' => 'Welcome.index',

    '/{name:string}' => 'Board.index',
    '/{name:string}/post' => 'Board.showPostingForm',

    '/post/{id:int}' => 'Post.index',
];
