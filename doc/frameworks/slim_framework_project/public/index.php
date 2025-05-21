<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Now Slim will auto-use slim/psr7 under the hood
$app = AppFactory::create();


$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("<html><body><h1>Welcome !</h1></body></html>");
    return $response;
});

// Define a route with a parameter
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = htmlspecialchars($args['name'], ENT_QUOTES);
    $response->getBody()->write("<html><body><h1>Hello, $name!</h1></body></html>");
    return $response;
});

// Run the application
$app->run();