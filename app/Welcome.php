<?php

namespace App\Controller;

use Core\Utils\EntityUtils\EntitySelect;
use Core\ViewModel;
use App\Entity\Board;

class Welcome
{
    public function index(ViewModel $viewModel)
    {
        $viewModel->set('boards', EntitySelect::select(Board::class)->findAll());
        return 'default/welcome';
    }
}
