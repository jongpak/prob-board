<?php

namespace App\Controller;

use App\Entity\User;
use Core\Application;
use App\Auth\LoginManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

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

    public function register($parsedBody, EntityManagerInterface $entityManager)
    {
        $user = new User();
        $user->setAccountId($parsedBody['account_id']);
        $user->setPassword($parsedBody['password']);
        $user->setNickname($parsedBody['nickname']);
        $user->setEmail($parsedBody['email']);

        $entityManager->persist($user);
        $entityManager->flush();

        return 'redirect:' . Application::getUrl();
    }
}
