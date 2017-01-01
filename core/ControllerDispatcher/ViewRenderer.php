<?php

namespace Core\ControllerDispatcher;

use Core\Event\EventManager;
use Core\ViewModel;
use Core\View\ViewEngineInterface;
use Core\View\ViewResolverInterface;
use Prob\Handler\Parameter\Typed;
use Prob\Handler\ParameterMap;

class ViewRenderer
{
    private $viewEngineConfig = [];
    private $viewResolvers = [];

    /**
     * @var ViewModel
     */
    private $viewModel;


    public function setViewEngineConfig(array $config)
    {
        $this->viewEngineConfig = $config;
    }

    public function setViewResolver(array $viewResolvers)
    {
        $this->viewResolvers = $viewResolvers;
    }

    public function setViewModel(ViewModel $viewModel)
    {
        $this->viewModel = $viewModel;
    }

    public function renderView($controllerResult)
    {
        $view = $this->resolveView($controllerResult);

        $parameterMap = new ParameterMap();
        $parameterMap->bindBy(new Typed(ViewModel::class), $this->viewModel);

        EventManager::trigger('ViewModelFilter', [$parameterMap]);

        foreach ($this->viewModel->getVariables() as $key => $value) {
            $view->set($key, $value);
        }

        return $view->render();
    }

    /**
     * @param  mixed $controllerResult
     * @return ViewEngineInterface
     */
    public function resolveView($controllerResult)
    {
        foreach ($this->viewResolvers as $name => $resolverClassName) {
            /** @var ViewResolverInterface $resolver */
            $resolver = new $resolverClassName();
            $resolver->setViewEngineConfig(
                isset($this->viewEngineConfig[$name])
                    ? $this->viewEngineConfig[$name]
                    : []
            );

            $view = $resolver->resolve($controllerResult);

            if ($view !== null) {
                return $view;
            }
        }
    }
}
