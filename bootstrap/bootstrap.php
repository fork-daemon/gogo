<?php

use App\Config;
use App\Service;

require_once '../vendor/autoload.php';

$config = require_once 'config.develop.php';
$service = require_once 'service.php';

Config::bulk($config);
Service::bulk($service);

