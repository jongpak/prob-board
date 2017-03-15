<?php

namespace App\EventListener;

use App\Auth\HashManager;
use App\Auth\HashProviderInterface;
use App\Auth\LoginManagerInterface;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Traits\UserContentable;
use App\EventListener\Auth\Exception\PermissionDenied;
use Core\Utils\EntityUtils\EntitySelect;
use Psr\Http\Message\ServerRequestInterface;

class UserContentAuth
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var LoginManagerInterface
     */
    private $loginManager;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var HashProviderInterface
     */
    private $hashProvider;

    public function __construct(ServerRequestInterface $request, LoginManagerInterface $loginManager)
    {
        $this->user = EntitySelect::select(User::class)
            ->criteria(['accountId' => $loginManager->getLoggedAccountId()])
            ->findOne();

        $this->loginManager = $loginManager;
        $this->request = $request;
        $this->hashProvider = HashManager::getProvider();
    }

    public function validatePostAuth($id, $parsedBody)
    {
        $this->validate(EntitySelect::select(Post::class)->findById($id), $parsedBody);
    }

    public function validateCommentAuth($id, $parsedBody)
    {
        $this->validate(EntitySelect::select(Comment::class)->findById($id), $parsedBody);
    }

    /**
     * @param UserContentable $userContent
     * @param $parsedBody
     * @throws PermissionDenied
     */
    private function validate($userContent, $parsedBody)
    {
        if ($userContent === null) {
            return;
        }

        if ($userContent->getUser() !== $this->user) {
            throw new PermissionDenied('This operation is not allowed');
        }

        if ($this->request->getMethod() === 'POST') {
            if($this->user !== null) {
                return;
            }

            if ($this->isValidGuestPassword($parsedBody['password'], $userContent->getPassword()) === false) {
                throw new PermissionDenied('Password is invalid');
            }
        }
    }

    private function isValidGuestPassword($password, $hashedPassword) {
        return $this->hashProvider->isEqualValueAndHash($password, $hashedPassword) === true;
    }
}
