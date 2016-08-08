<?php

namespace Core;

use Prob\Router\Dispatcher;
use Prob\Rewrite\Request;

class Framework
{
    private $map;
    private $siteConfig = [];
    private $viewEngineConfig = [];

    public function boot()
    {
        $this->setShowError();

        $this->loadSiteConfig();
        $this->loadViewEngineConfig();
        $this->loadRouterMap();

        $this->dispatcher();
    }

    public function setShowError()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'on');
    }

    public function loadRouterMap()
    {
        $this->map = require '../config/router.php';
    }

    public function loadSiteConfig()
    {
        $this->siteConfig = require '../config/site.php';
    }

    public function loadViewEngineConfig()
    {
        $this->viewEngineConfig = require '../config/viewEngine.php';
    }

    public function dispatcher()
    {
        $result = null;

        $dispatcher = new Dispatcher($this->map);
        $result = $dispatcher->dispatch(new Request());

        $viewResolver = new ViewResolver($result);
        $view = $viewResolver->resolve($this->viewEngineConfig[$this->siteConfig['viewEngine']]);
        $view->render();
    }
}