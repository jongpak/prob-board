<?php

namespace App\EventListener\Auth;

use Prob\Handler\ProcInterface;
use App\Auth\AuthManager;
use App\Auth\LoginManagerInterface;
use App\EventListener\Auth\Exception\PermissionDenied;

class ValidatorListener
{
    public function validate(ProcInterface $proc, LoginManagerInterface $loginManager)
    {
        if ($this->isAllowedRole($proc, $loginManager) === false) {
            throw new PermissionDenied('This operation is not allowed');
        }

        return true;
    }

    private function isAllowedRole(ProcInterface $proc, LoginManagerInterface $loginManager) {
        return AuthManager::getPermissionManager()
            ->hasAllowedRole($loginManager->getLoggedAccountId(), $proc->getName());
    }
}
