<?php

return [
    'site' => require 'site.php',
    'auth' => require __DIR__ . '/../app/Auth/config/config.php',
    'db' => require 'db.php',
    'viewEngine' => require 'viewEngine.php',
    'viewResolver' => require 'viewResolver.php',
    'router' => require 'router.php',
    'error' => require 'error.php',
    'event' => require 'event.php',
    'parameter' => require 'parameter.php',
    'formValidation' => require 'formValidation.php',
];
