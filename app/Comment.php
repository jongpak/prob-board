<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Entity\Comment as CommentModel;
use Doctrine\ORM\EntityManagerInterface;
use \DateTime;

class Comment
{
    public function showEditForm($id, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $viewModel->set('comment', $entityManager->getRepository(CommentModel::class)->find($id));

        return 'default/commentForm';
    }

    public function edit($id, $parsedBody, EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        /** @var CommentModel */
        $comment = $entityManager->getRepository(CommentModel::class)->find($id);
        $comment->setAuthor($parsedBody['author']);
        $comment->setContent($parsedBody['content']);
        $comment->setUpdatedAt(new DateTime());

        $entityManager->flush();

        return 'redirect: ' . Application::getUrl('/post/' . $comment->getPost()->getId());
    }
}
