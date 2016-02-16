<?php

define('ROOT', realpath(__DIR__ . '/../') . '/');
require_once ROOT . '/vendor/autoload.php';

define('ENV', 'develop'); //  : [develop  production , stage]
define('CLI', php_sapi_name() === 'cli'); //  : [true , false]


$config = require_once ROOT . 'bootstrap/config.develop.php';
$locale = require_once ROOT . 'locale/en.php';
$service = require_once ROOT . 'bootstrap/service.php';

\App\Config::extend($config);
\App\Locale::extend($locale);
\App\Service::extend($service);

