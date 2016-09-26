<?php

namespace App\Utils;

use App\Auth\LoginManagerInterface;
use App\Entity\User;
use Core\DatabaseManager;

class ContentUserInfoSetter
{
    public static function fillUserInfo($content, $parsedBody, LoginManagerInterface $loginManager)
    {
        if ($loginManager->getLoggedAccountId()) {
            /** @var User */
            $user = DatabaseManager::getEntityManager()->getRepository(User::class)
                        ->findOneBy(['accountId' => $loginManager->getLoggedAccountId()]);

            $content->setUser($user);
            $content->setAuthor($user->getNickname());
            $content->setPassword($user->getPassword());
        } else {
            $content->setAuthor($parsedBody['author']);
            $content->setPassword($parsedBody['password']);
        }
    }
}
