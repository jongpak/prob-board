<?php

namespace App\Bootstrap;

use App\EventListener\FormValidator;
use Core\Bootstrap\BootstrapInterface;

class FormValidatorBootstrap implements BootstrapInterface
{
    public function boot(array $env)
    {
        FormValidator::setRule($env['formValidation']);
    }
}