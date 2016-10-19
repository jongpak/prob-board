<?php

namespace App\Service;

use App\Auth\LoginManagerInterface;
use App\Entity\Board;
use App\Entity\Post;
use App\Exception\EntityNotFound;
use App\Utils\ContentUserInfoSetter;
use Core\Utils\EntityFinder;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class PostService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var AttachmentService
     */
    private $attachmentService;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->attachmentService = new AttachmentService($entityManager);
    }

    public function getPostEntity($id)
    {
        $post = EntityFinder::findById(Post::class, $id);

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

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    public function editPost(Post $post, $body, LoginManagerInterface $loginManager)
    {
        $post->setSubject($body['subject']);
        $post->setContent($body['content']);
        $post->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($post, $body, $loginManager);

        $this->entityManager->flush();
    }

    public function deletePost(Post $post)
    {
        $post->setBoard(null);
        $this->entityManager->flush();
    }

    public function attachFile(Post $post, array $fileRequests)
    {
        $files = $this->attachmentService->uploadFile($fileRequests);
        foreach ($files as $file) {
            $post->addAttachmentFile($file);
        }
        $this->entityManager->flush();
    }

    public function detachFile(array $fileId)
    {
        $this->attachmentService->deleteFile($fileId);
    }
}