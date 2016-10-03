<?php

namespace App\Controller;

use Core\ViewModel;
use Core\Utils\EntityFinder;
use App\Entity\Board;

class Welcome
{

    public function index(ViewModel $viewModel)
    {
        $viewModel->set('boards', EntityFinder::findAll(Board::class));
        return 'default/welcome';
    }
}
