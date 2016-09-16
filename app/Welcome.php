<?php

namespace App\Controller;

use Core\ViewModel;
use App\Entity\Board;
use Doctrine\ORM\EntityManagerInterface;

class Welcome
{

    public function index(EntityManagerInterface $entityManager, ViewModel $viewModel)
    {
        $boards = $entityManager->getRepository(Board::class)->findAll();

        $viewModel->set('boards', $boards);

        return 'default/welcome';
    }
}
