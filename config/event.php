<?php

return [
    'Controller' => [
        '*.*' => [
            'before' => [
                'App\\EventListener\\Auth\\ValidatorListener.validate',
                'App\\EventListener\\TemplateCommon.putVariable'
            ]
        ],

        'Post.showEditForm.before' => 'App\\EventListener\\ControllerAuth.validatePostEdit',
        'Post.edit.before' => 'App\\EventListener\\ControllerAuth.validatePostEdit',

        'Comment.showEditForm.before' => 'App\\EventListener\\ControllerAuth.validateCommentEdit',
        'Comment.edit.before' => 'App\\EventListener\\ControllerAuth.validateCommentEdit',
    ]
];
