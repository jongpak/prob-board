<?php

namespace App\Utils;

use App\Auth\AuthManager;
use App\Auth\HashManager;
use App\Auth\LoginManagerInterface;
use App\Entity\User;
use App\Entity\Traits\UserContentable;
use Core\Utils\EntityUtils\EntitySelect;
use \InvalidArgumentException;

class ContentUserInfoSetter
{
    /**
     * @param $content UserContentable
     * @param $parsedBody
     * @param LoginManagerInterface $loginManager
     */
    public static function fillUserInfo($content, $parsedBody, LoginManagerInterface $loginManager)
    {
        self::validateUsingUserContenable($content);

        if ($loginManager->getLoggedAccountId()) {
            /** @var User */
            $user = EntitySelect::select(User::class)
                ->criteria(['accountId' => $loginManager->getLoggedAccountId()])
                ->findOne();

            $content->setUser($user);
        } else {
            $content->setAuthor($parsedBody['author']);
            $content->setPassword(HashManager::getProvider()->make($parsedBody['password']));
        }
    }

    private static function validateUsingUserContenable($obj)
    {
        if (in_array(UserContentable::class, class_uses($obj)) === false) {
            throw new InvalidArgumentException(get_class($obj) . ' must using trait ' . UserContentable::class);
        }
    }
}
