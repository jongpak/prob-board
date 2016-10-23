<?php

namespace App\Controller;

use Core\Application;
use App\Auth\LoginManagerInterface;

class Auth
{
    public function viewLoginForm()
    {
        return 'auth/login';
    }

    public function viewRegisterForm()
    {
        return 'auth/register';
    }

    public function login(LoginManagerInterface $loginManager, $parsedBody)
    {
        $loginManager->login($parsedBody['account_id'], $parsedBody['password']);
        return 'redirect: ' . Application::getUrl();
    }

    public function logout(LoginManagerInterface $loginManager)
    {
        $loginManager->logout();
        return 'redirect: ' . Application::getUrl();
    }
}
