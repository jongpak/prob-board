<?php

namespace App\Controller;

use Core\Utils\EntityUtils\EntitySelect;
use Core\ViewModel;
use App\Entity\Board as BoardModel;

class Welcome
{
    public function index(ViewModel $viewModel)
    {
        $viewModel->set('boards', EntitySelect::select(BoardModel::class)->findAll());
        return 'welcome';
    }
}
