<?php

return [
    'memcache'           => function () {
        $config = \App\Config::get('memcache');

        return new \App\Memcache\Client($config['client']);
    },
    'logger'             => function () {
        // create a log channel

        $loggerLevel = \App\Config::get('logger.level', \Monolog\Logger::WARNING);
        // $loggerLevel = \App\Config::get('logger.path', \Monolog\Logger::WARNING);

        $log = new \Monolog\Logger('name');
        $log->pushHandler(new \Monolog\Handler\StreamHandler('path/to/your.log', $loggerLevel));
        
        // add records to the log
        // $log->addWarning('Foo');
        // $log->addError('Bar');

        return $log;
    },
    'elastic'            => function () {
        $config = \App\Config::get('elastic');

        return new \App\Elastic\Client($config['client']);
    },
];