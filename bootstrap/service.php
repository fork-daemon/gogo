<?php

return [
    'memcache' => function () {
        $config = \App\Config::get('memcache');

        return new \App\Memcache\Client($config['client']);
    },
    'logger'   => function () {
        $config = \App\Config::get('logger');
        $log = new \Monolog\Logger($config['name']);
        $formatter = new \Monolog\Formatter\LineFormatter(
            '%datetime% %channel% %level_name% : %message% %context% ' . PHP_EOL,
            'Y-m-d H:i:s'
        );
        $stream = new \Monolog\Handler\StreamHandler($config['path'], $config['level']);
        $stream->setFormatter($formatter);
        $log->pushHandler($stream);

        return $log;
    },
    'elastic'  => function () {
        $config = \App\Config::get('elastic');

        return new \Lib\Elastic\Client($config['client']);
    },
];