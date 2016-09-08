<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Application;
use App\Auth\AuthManager;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use Doctrine\ORM\EntityManager;

class Board
{

    public function viewPostList($name, ViewModel $viewModel)
    {
        /** @var EntityManager */
        $entityManager = Application::getInstance()->getEntityManager();

        $board = $entityManager->getRepository(BoardModel::class)->findOneBy(['name' => $name]);
        $posts = $entityManager->getRepository(PostModel::class)->findBy(['board' => $board->getId()]);

        $viewModel->set('board', $board);
        $viewModel->set('posts', $posts);

        return 'default/postList';
    }
}
