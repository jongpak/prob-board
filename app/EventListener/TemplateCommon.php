<?php

namespace App\EventListener;

use Core\ViewModel;
use App\Auth\LoginManagerInterface;

class TemplateCommon
{
    public function putVariable(LoginManagerInterface $loginManager, ViewModel $viewModel)
    {
        $viewModel->set('loggedAccountId', $loginManager->getLoggedAccountId());
    }
}
