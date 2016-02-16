<?php

use App\Config;
use App\Service;
use App\Locale;

require_once '../vendor/autoload.php';

define('ENV', 'develop'); //  : [develop  production , stage]
define('ROOT', realpath(__DIR__ . '/../'));
define('CLI', php_sapi_name() === 'cli'); //  : [true , false]

$config = require_once 'config.develop.php';
$locale = require_once 'locale.php';
$service = require_once 'service.php';

Config::bulk($config);
Locale::bulk($locale);
Service::bulk($service);

