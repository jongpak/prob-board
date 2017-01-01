<?php

namespace App\ViewModelFilter\Filter;

use App\ViewModelFilter\ViewModelFilterInterface;
use Core\ControllerDispatcher\RequestMatcher;
use Core\ViewModel;
use Parsedown;

class MarkdownFilter implements ViewModelFilterInterface
{
    public function filter(ViewModel $viewModel)
    {
        foreach ($viewModel->getVariables() as $key => $value) {
            if($this->isContent($key)) {
                $value->setContent($this->markdown($value->getContent()));
            }
        }
    }

    private function isContent($key) {
        if(strpos(RequestMatcher::getControllerProc()->getName(), 'showEditForm') !== false) {
            return false;
        }

        return $key === 'post' || $key === 'comment';
    }

    private function markdown($str) {
        $parsedown = new Parsedown();
        $parsedown->setMarkupEscaped(true);

        return $parsedown->text($str);
    }
}