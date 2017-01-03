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
                $this->parseMarkdown($value);
            }

            if($this->isContentList($key)) {
                foreach ($value as $item) {
                    $this->parseMarkdown($item);
                }
            }
        }
    }

    private function parseMarkdown($content) {
        $content->setContent($this->markdown($content->getContent()));
    }

    private function isContent($key)
    {
        if(strpos(RequestMatcher::getControllerProc()->getName(), 'showEditForm') !== false) {
            return false;
        }

        return $key === 'post' || $key === 'comment';
    }

    private function isContentList($key)
    {
        return $key === 'posts' || $key === 'comments';
    }

    private function markdown($str)
    {
        $parsedown = new Parsedown();
        $parsedown->setMarkupEscaped(true);

        return $parsedown->text($str);
    }
}