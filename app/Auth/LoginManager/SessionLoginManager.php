<?php

namespace App\Auth\LoginManager;

use App\Auth\AuthManager;
use App\Auth\LoginManagerInterface;
use App\Auth\AccountManagerInterface;
use App\Auth\Exception\AccountNotFound;

class SessionLoginManager implements LoginManagerInterface
{
    /**
     * @var AccountManagerInterface
     */
    private $accountManager;

    public function __construct(array $settings = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->accountManager = AuthManager::getAccountManager();
    }

    public function login($accountId, $password)
    {
        if ($this->isValidAccountInfo($accountId, $password)) {
            throw new AccountNotFound('Invalid account id or password');
        }

        $_SESSION['account'] = [
            'id' => $accountId
        ];
    }

    private function isValidAccountInfo($accountId, $password)
    {
        return $this->accountManager->isExistAccountId($accountId)
                && $this->accountManager->isEqualPassword($accountId, $password);

    }

    public function logout()
    {
        unset($_SESSION['account']);
    }

    public function isLogged()
    {
        return isset($_SESSION['account']) && isset($_SESSION['account']['id']);
    }

    public function getLoggedAccountId()
    {
        if ($this->isLogged() === false) {
            return;
        }

        return $_SESSION['account']['id'];
    }
}
