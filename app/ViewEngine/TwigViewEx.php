<?php

namespace App\ViewEngine;

use \Twig_SimpleFunction;

class TwigViewEx extends TwigView
{
    public function __construct($settings = [])
    {
        parent::__construct($settings);

        $this->loadExtendFunctions($settings['functions']);
    }

    private function loadExtendFunctions(array $functions)
    {
        foreach ($functions as $name => $func) {
            $this->twig->addFunction(new Twig_SimpleFunction($name, $func));
        }
    }
}
