<?php

namespace App\Controller;

use App\Entity\User;
use Core\Application;
use App\Auth\LoginManagerInterface;
use Core\Utils\EntityUtils\EntityInsert;

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

    public function register($parsedBody)
    {
        $user = new User();
        $user->setAccountId($parsedBody['account_id']);
        $user->setPassword($parsedBody['password']);
        $user->setNickname($parsedBody['nickname']);
        $user->setEmail($parsedBody['email']);

        EntityInsert::insert($user);

        return 'redirect:' . Application::getUrl();
    }
}
