<?php

namespace App\EventListener;

use App\ViewModelFilter\ViewModelFilterInterface;
use App\Controller\Admin\AdminService;
use Core\ViewModel;

class ViewModelFilter
{
    public function filter(ViewModel $viewModel) {
        $config = AdminService::getEnvironment('viewModelFilter');

        foreach ($config['enables'] as $enableFilter) {
            $className = $config['filters'][$enableFilter]['class'];
            $setting = $config['filters'][$enableFilter]['settings'];

            /**
             * @var ViewModelFilterInterface $filter
             */
            $filter = new $className($setting);
            $filter->filter($viewModel);
        }
    }
}
