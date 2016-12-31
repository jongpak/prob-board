<?php

return [
    'Controller' => [
        '*.*' => [
            'before' => [
                'App\\EventListener\\Auth\\PermissionVerificationListener.validate',
                'App\\EventListener\\TemplateCommon.putVariable',
                'App\\EventListener\\FormValidator.validate',
            ]
        ],

        'Post.showEditForm.before' => 'App\\EventListener\\ControllerAuth.validatePostAuth',
        'Post.edit.before' => 'App\\EventListener\\ControllerAuth.validatePostAuth',
        'Post.showDeleteForm.before' => 'App\\EventListener\\ControllerAuth.validatePostAuth',
        'Post.delete.before' => 'App\\EventListener\\ControllerAuth.validatePostAuth',

        'Comment.showEditForm.before' => 'App\\EventListener\\ControllerAuth.validateCommentAuth',
        'Comment.edit.before' => 'App\\EventListener\\ControllerAuth.validateCommentAuth',
        'Comment.showDeleteForm.before' => 'App\\EventListener\\ControllerAuth.validateCommentAuth',
        'Comment.delete.before' => 'App\\EventListener\\ControllerAuth.validateCommentAuth',
    ]
];
