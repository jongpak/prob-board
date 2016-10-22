<?php

namespace App\Bootstrap;

use App\EventListener\FormValidator;
use Core\Bootstrap\BootstrapInterface;
use Prob\Handler\ProcFactory;

class FormValidatorBootstrap implements BootstrapInterface
{
    public function boot(array $env)
    {
        $this->validateController($env['formValidation'], $env['router']['namespace']);

        FormValidator::setRule($env['formValidation']);
    }

    public function validateController(array $rule, $namespace)
    {
        $keys = array_keys($rule);
        foreach($keys as $key) {
            ProcFactory::getProc($namespace . '\\' . $key);
        }
    }
}