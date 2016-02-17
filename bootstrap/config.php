<?php

return [
    'owner' => 'production',
    'logger' => [
        'level' => \Monolog\Logger::WARNING,
        'path' => ROOT . 'logs/log-'.date('Y-m-d').'.log',
        'name' => 'app',
    ],
    'rebbitMQ' => [
        'host' => 'localhost',
        'port' => 5672,
        'user' => 'user',
        'password' => 'password',
        'vhost' => '/',
        'debug' => false,
    ],
];