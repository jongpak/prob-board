<?php

namespace App\Controller;

use Core\ViewModel;
use App\Auth\AuthManager;

class Welcome
{
    public function index(ViewModel $viewModel)
    {
        $accountManager = AuthManager::getInstance()->getDefaultAccountManager();
        $loginManager = AuthManager::getInstance()->getDefaultLoginManager();

        $loggedAccountId = $loginManager->getLoggedAccountId();

        $viewModel->set('boards', ['free', 'qna', 'etc']);

        return 'default/welcome';
    }
}
