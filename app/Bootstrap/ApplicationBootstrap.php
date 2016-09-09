<?php

namespace App\Bootstrap;

use Core\Application;
use Core\Bootstrap\BootstrapInterface;
use Core\Bootstrap\SiteConfigLoader;

class ApplicationBootstrap implements BootstrapInterface, SiteConfigLoader
{

    public function boot(Application $app)
    {
        $app->setSiteConfig($this->getSiteConfig());

        $app->setViewEngineConfig($this->getViewEngineConfig());
        $app->setViewResolver($this->getViewResolvers());

        $app->setRouterConfig($this->getRouterConfig());
    }

    public function getSiteConfig()
    {
        return require __DIR__ . '/../../config/site.php';
    }

    private function getViewEngineConfig()
    {
        return require __DIR__ . '/../../config/viewEngine.php';
    }

    private function getViewResolvers()
    {
        return require __DIR__ . '/../../config/viewResolver.php';
    }

    private function getRouterConfig()
    {
        return require __DIR__ . '/../../config/router.php';
    }
}
