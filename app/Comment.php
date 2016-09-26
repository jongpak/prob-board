<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Comment as CommentModel;
use App\Entity\User as UserModel;
use App\Utils\ContentUserInfoSetter;
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

    public function edit($id, $parsedBody, EntityManagerInterface $entityManager, LoginManagerInterface $loginManager)
    {
        /** @var CommentModel */
        $comment = $entityManager->getRepository(CommentModel::class)->find($id);
        $comment->setContent($parsedBody['content']);
        $comment->setUpdatedAt(new DateTime());
        ContentUserInfoSetter::fillUserInfo($comment, $parsedBody, $loginManager);

        $entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $comment->getPost()->getId());
    }
}
