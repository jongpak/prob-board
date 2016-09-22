<?php

namespace App\EventListener;

use App\Auth\LoginManagerInterface;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\EventListener\Auth\Exception\PermissionDenied;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;

class ControllerAuth
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $em, LoginManagerInterface $loginManager)
    {
        $this->entityManager = $em;
        $this->user = $em->getRepository(User::class)->findOneBy(['accountId' => $loginManager->getLoggedAccountId()]);
    }

    public function validatePostEdit($id, $parsedBody, ServerRequestInterface $request)
    {
        $this->validate(Post::class, $id, $parsedBody, $request);
    }

    public function validateCommentEdit($id, $parsedBody, ServerRequestInterface $request)
    {
        $this->validate(Comment::class, $id, $parsedBody, $request);
    }

    private function validate($entityClassName, $id, $parsedBody, ServerRequestInterface $request)
    {
        /** @var Post */
        $content = $this->entityManager->getRepository($entityClassName)->find($id);

        if ($request->getMethod() === 'POST' && $this->user === null && $content->getPassword() !== $parsedBody['password']) {
            throw new PermissionDenied('Password is invalid');
        }

        if ($content->getUser() !== $this->user) {
            throw new PermissionDenied('This operation is not allowed');
        }
    }
}
