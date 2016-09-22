<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Comment as CommentModel;
use App\Entity\User as UserModel;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use \DateTime;

class Comment
{
    public function showEditForm($id, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $viewModel->set('comment', $entityManager->getRepository(CommentModel::class)->find($id));

        return 'default/commentForm';
    }

    public function edit($id, $parsedBody, EntityManagerInterface $entityManager, LoginManagerInterface $loginManager, ViewModel $viewModel)
    {
        /** @var CommentModel */
        $comment = $entityManager->getRepository(CommentModel::class)->find($id);
        $comment->setContent($parsedBody['content']);
        $comment->setUpdatedAt(new DateTime());
        $this->fillUserInfomanionByLoginAccount($comment, $parsedBody, $loginManager, $entityManager);

        $entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $comment->getPost()->getId());
    }

    private function fillUserInfomanionByLoginAccount($userContent, $parsedBody, LoginManagerInterface $loginManager, EntityManagerInterface $entityManager)
    {
        if ($loginManager->getLoggedAccountId()) {
            /** @var UserModel */
            $user = $entityManager->getRepository(UserModel::class)
                        ->findOneBy(['accountId' => $loginManager->getLoggedAccountId()]);

            $userContent->setUser($user);
            $userContent->setAuthor($user->getNickname());
            $userContent->setPassword($user->getPassword());
        } else {
            $userContent->setAuthor($parsedBody['author']);
            $userContent->setPassword($parsedBody['password']);
        }
    }
}
