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

        'Post.showEditForm.before' => 'App\\EventListener\\UserContentAuth.validatePostAuth',
        'Post.edit.before' => 'App\\EventListener\\UserContentAuth.validatePostAuth',
        'Post.showDeleteForm.before' => 'App\\EventListener\\UserContentAuth.validatePostAuth',
        'Post.delete.before' => 'App\\EventListener\\UserContentAuth.validatePostAuth',

        'Comment.showEditForm.before' => 'App\\EventListener\\UserContentAuth.validateCommentAuth',
        'Comment.edit.before' => 'App\\EventListener\\UserContentAuth.validateCommentAuth',
        'Comment.showDeleteForm.before' => 'App\\EventListener\\UserContentAuth.validateCommentAuth',
        'Comment.delete.before' => 'App\\EventListener\\UserContentAuth.validateCommentAuth',
    ],

    'ViewModelFilter' => 'App\\EventListener\\ViewModelFilter.filter',
];
