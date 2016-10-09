<?php

namespace App\ViewResolver;

use App\ViewEngine\TwigViewEx;

class TwigExResolver extends TwigResolver
{
    public function resolve($viewData)
    {
        if (is_string($viewData)) {
            $view = new TwigViewEx($this->settings);
            $view->file($viewData);

            return $view;
        }
    }
}
