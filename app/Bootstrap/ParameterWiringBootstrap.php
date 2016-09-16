<?php

namespace App\Bootstrap;

use Core\Bootstrap\BootstrapInterface;
use Core\ParameterWire;

class ParameterWiringBootstrap implements BootstrapInterface
{

    public function boot(array $env)
    {
        foreach ($env['parameter'] as $key => $value) {
            ParameterWire::appendParameter($key, $value);
        }
    }
}
