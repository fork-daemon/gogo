<?php

define('ROOT', realpath(__DIR__ . '/../') . '/');
require_once ROOT . '/vendor/autoload.php';

define('ENV', 'develop'); //  : [develop  production , stage]
define('CLI', php_sapi_name() === 'cli'); //  : [true , false]


$config = require_once ROOT . 'bootstrap/config.php';
\App\Config::extend($config);

$configDevelop = require_once ROOT . 'bootstrap/config.develop.php';
\App\Config::extend($configDevelop);

$locale = require_once ROOT . 'locale/en.php';
\App\Locale::extend($locale);

$service = require_once ROOT . 'bootstrap/service.php';
\App\Service::extend($service);

