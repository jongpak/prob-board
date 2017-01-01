<?php

return [
    'site' => require 'site.php',
    'auth' => require __DIR__ . '/../app/Auth/config/config.php',
    'hashProvider' => require __DIR__ . '/../app/Auth/config/hashProvider.php',
    'db' => require 'db.php',
    'viewEngine' => require 'viewEngine.php',
    'viewResolver' => require 'viewResolver.php',
    'viewPrefix' => require 'viewPrefix.php',
    'router' => require 'router.php',
    'error' => require 'error.php',
    'event' => require 'event.php',
    'parameter' => require 'parameter.php',
    'formValidation' => require 'formValidation.php',
    'dispatcher' => require 'dispatcher.php',
    'viewModelFilter' => require __DIR__ . '/../app/ViewModelFilter/config.php',
];
