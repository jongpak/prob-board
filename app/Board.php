<?php

namespace App\Controller;

use Core\ViewModel;
use Core\DatabaseManager;
use App\Entity\Post as PostModel;
use App\Entity\Board as BoardModel;
use Doctrine\ORM\EntityManager;

class Board
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = DatabaseManager::getEntityManager();
    }

    public function index($name, ViewModel $viewModel)
    {
        $board = $this->entityManager->getRepository(BoardModel::class)->findOneBy(['name' => $name]);
        $posts = $this->entityManager->getRepository(PostModel::class)->findBy(['board' => $board->getId()]);

        $viewModel->set('board', $board);
        $viewModel->set('posts', $posts);

        return 'default/postList';
    }

    public function showPostingForm($name, ViewModel $viewModel)
    {
        $board = $this->entityManager->getRepository(BoardModel::class)->findOneBy(['name' => $name]);
        $viewModel->set('board', $board);

        return 'default/postWriteForm';
    }
}
