<?php

namespace App\Controller;

use Core\ViewModel;
use Core\DatabaseManager;
use App\Entity\Board;
use Doctrine\ORM\EntityManager;

class Welcome
{
    public function index(ViewModel $viewModel)
    {
        /** @var EntityManager */
        $entityManager = DatabaseManager::getDefaultEntityManager();
        $boards = $entityManager->getRepository(Board::class)->findAll();

        $viewModel->set('boards', $boards);

        return 'default/welcome';
    }
}
