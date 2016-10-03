<?php

namespace App\Utils;

use App\Auth\LoginManagerInterface;
use App\Entity\User;
use App\Entity\Traits\UserContentable;
use Core\DatabaseManager;
use Core\Utils\EntityFinder;
use \InvalidArgumentException;

class ContentUserInfoSetter
{
    public static function fillUserInfo($content, $parsedBody, LoginManagerInterface $loginManager)
    {
        self::validateUsingUserContenable($content);

        if ($loginManager->getLoggedAccountId()) {
            /** @var User */
            $user = EntityFinder::findOneBy(User::class, ['accountId' => $loginManager->getLoggedAccountId()]);
            $content->setUser($user);
        } else {
            $content->setAuthor($parsedBody['author']);
            $content->setPassword($parsedBody['password']);
        }
    }

    private static function validateUsingUserContenable($obj)
    {
        if (in_array(UserContentable::class, class_uses($obj)) === false) {
            throw new InvalidArgumentException(get_class($obj) . ' must using trait ' . UserContentable::class);
        }
    }
}
