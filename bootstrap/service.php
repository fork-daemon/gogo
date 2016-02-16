<?php

return [
    'memcache'           => function () {
        $config = \App\Config::get('memcache');

        return new \App\Memcache\Client($config['client']);
    },
    'logger'             => function () {
        $config = \App\Config::get('logger');

        $obj = new \stdClass();
        $obj->config = $config;

        return $obj;
    },
    'elastic'            => function () {
        $config = \App\Config::get('elastic');

        return new \App\Elastic\Client($config['client']);
    },
];