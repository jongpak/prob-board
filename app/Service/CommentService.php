<?php

namespace App\Service;

use App\Auth\LoginManagerInterface;
use App\Entity\Post;
use App\Entity\Comment;
use App\Exception\EntityNotFound;
use App\Utils\ContentUserInfoSetter;
use Core\Utils\EntityFinder;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
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

    public function getCommentEntity($id)
    {
        $comment = EntityFinder::findById(Comment::class, $id);

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

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

    public function editComment(Comment $comment, $body, LoginManagerInterface $loginManager)
    {
        $comment->setContent($body['content']);
        $comment->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($comment, $body, $loginManager);

        $this->entityManager->flush();
    }

    public function deleteComment(Comment $comment)
    {
        $comment->setPost(null);
        $this->entityManager->flush();
    }

    public function attachFile(Comment $comment, array $fileRequests)
    {
        $files = $this->attachmentService->uploadFile($fileRequests);
        foreach ($files as $file) {
            $comment->addAttachmentFile($file);
        }
        $this->entityManager->flush();
    }

    public function detachFile(array $fileId)
    {
        $this->attachmentService->deleteFile($fileId);
    }
}