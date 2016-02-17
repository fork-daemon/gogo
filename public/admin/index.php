<?php

require_once '../../bootstrap/bootstrap.php';

$app = new Slim\App();

$app->get('/', function ($request, $response, $args) {
    $response->write("123 Welcome to Slim!");
    return $response;
});

$app->get('/hello[/{name}]', function ($request, $response, $args) {
    $response->write("123 Hello, " . $args['name']);
    return $response;
})->setArgument('name', 'World!');

$app->run();