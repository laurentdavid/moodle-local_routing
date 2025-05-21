<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Laminas\Diactoros\ServerRequestFactory;

function handleHello(array $vars): Response
{
    $name = (int) $vars['name'];

    $body = sprintf(
        '<html><body><h1>Hello %s !</h1></body></html>',
        $name,
    );

    $response = new Response();
    $response->getBody()->write($body);
    return $response;
}

// 1) Create a PSR-7 request from globals
$request = ServerRequestFactory::fromGlobals();

// 2) Configure FastRoute dispatcher
$dispatcher = simpleDispatcher(function(RouteCollector $r) {
    // Define GET /hello/{name} route
    $r->addRoute(
        'GET',
        '/hello/{name:[a-z0-9\-]+}',
        'handleHello'
    );
});

// 3) Extract HTTP method and URI (without query string)
$httpMethod = $request->getMethod();
$uri        = $request->getUri()->getPath();
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

// 4) Dispatch and handle
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 Not Found';
        return;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 Method Not Allowed';
        return;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars    = $routeInfo[2];
        $response = call_user_func($handler, $vars);
        // Assume handler returns a Laminas\Http\ResponseInterface
        http_response_code($response->getStatusCode());
        echo $response->getBody();
        return;
}