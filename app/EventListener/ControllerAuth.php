<?php

namespace App\EventListener;

use App\Auth\LoginManagerInterface;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Traits\UserContentable;
use App\EventListener\Auth\Exception\PermissionDenied;
use Core\Utils\EntityUtils\EntitySelect;
use Psr\Http\Message\ServerRequestInterface;

class ControllerAuth
{
    /**
     * @var User
     */
    private $user;

    public function __construct(LoginManagerInterface $loginManager)
    {
        $this->user = EntitySelect::select(User::class)
            ->criteria(['accountId' => $loginManager->getLoggedAccountId()])
            ->findOne();
    }

    public function validatePostAuth($id, $parsedBody, ServerRequestInterface $request)
    {
        $this->validate(EntitySelect::select(Post::class)->findById($id), $parsedBody, $request);
    }

    public function validateCommentAuth($id, $parsedBody, ServerRequestInterface $request)
    {
        $this->validate(EntitySelect::select(Comment::class)->findById($id), $parsedBody, $request);
    }

    private function validate($userContent, $parsedBody, ServerRequestInterface $request)
    {
        if ($userContent === null) {
            return;
        }

        if ($userContent->getUser() !== $this->user) {
            throw new PermissionDenied('This operation is not allowed');
        }

        if ($request->getMethod() === 'POST') {
            if ($this->user === null && $userContent->getPassword() !== $parsedBody['password']) {
                throw new PermissionDenied('Password is invalid');
            }
        }
    }
}
