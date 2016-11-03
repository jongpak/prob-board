<?php

namespace App\Service;

use App\Auth\LoginManagerInterface;
use App\Entity\Post;
use App\Entity\Comment;
use App\Exception\EntityNotFound;
use App\Utils\ContentUserInfoSetter;
use Core\Utils\EntityUtils\EntityInsert;
use Core\Utils\EntityUtils\EntitySelect;
use Core\Utils\EntityUtils\EntityUpdate;
use DateTime;

class CommentService
{
    public function getCommentEntity($id)
    {
        $comment = EntitySelect::select(Comment::class)->findById($id);

        if ($comment === null) {
            throw new EntityNotFound('Comment is not found');
        }

        return $comment;
    }

    public function writeComment(Post $post, $body, LoginManagerInterface $loginManager)
    {
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setContent($body['content']);
        ContentUserInfoSetter::fillUserInfo($comment, $body, $loginManager);

        EntityInsert::insert($comment);

        return $comment;
    }

    public function editComment(Comment $comment, $body, LoginManagerInterface $loginManager)
    {
        $comment->setContent($body['content']);
        $comment->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($comment, $body, $loginManager);

        EntityUpdate::update($comment);
    }

    public function deleteComment(Comment $comment)
    {
        $comment->setPost(null);
        EntityUpdate::update($comment);
    }
}