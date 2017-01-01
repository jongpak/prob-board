<?php

namespace App\ViewModelFilter;

use Core\ViewModel;

interface ViewModelFilterInterface
{
    public function filter(ViewModel $viewModel);
}