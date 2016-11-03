<?php

namespace App\Service;

use App\Auth\LoginManagerInterface;
use App\Entity\Board;
use App\Entity\Post;
use App\Exception\EntityNotFound;
use App\Utils\ContentUserInfoSetter;
use Core\Utils\EntityUtils\EntityInsert;
use Core\Utils\EntityUtils\EntitySelect;
use Core\Utils\EntityUtils\EntityUpdate;
use DateTime;

class PostService
{
    public function getPostEntity($id)
    {
        $post = EntitySelect::select(Post::class)->findById($id);

        if ($post === null) {
            throw new EntityNotFound('Post is not found');
        }

        return $post;
    }

    public function writePost(Board $board, $body, LoginManagerInterface $loginManager)
    {
        $post = new Post();
        $post->setBoard($board);
        $post->setSubject($body['subject']);
        $post->setContent($body['content']);
        ContentUserInfoSetter::fillUserInfo($post, $body, $loginManager);

        EntityInsert::insert($post);

        return $post;
    }

    public function editPost(Post $post, $body, LoginManagerInterface $loginManager)
    {
        $post->setSubject($body['subject']);
        $post->setContent($body['content']);
        $post->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($post, $body, $loginManager);

        EntityUpdate::update($post);
    }

    public function deletePost(Post $post)
    {
        $post->setBoard(null);
        EntityUpdate::update($post);
    }
}