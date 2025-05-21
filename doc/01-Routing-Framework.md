# 01 – Routing in PHP Frameworks

In this module, we’ll explore routing patterns across popular PHP frameworks—Symfony, Laravel—and the two systems used by Moodle: Slim Framework and FastRoute. You’ll see how each defines routes, handles parameters, and directs requests to controllers.

## What You’ll Learn

* **Symfony Routing**: Declarative routes in YAML/XML/PHP; PHP 8 attribute syntax; parameter requirements; route names.
* **Laravel Routing**: Fluent syntax; closures vs. controllers; route groups and middleware.
* **Slim Framework**: Minimalistic approach; PSR-7 integration; route callbacks. (Used in Moodle core.)
* **FastRoute**: High-performance routing; dispatcher setup; route definitions in PHP arrays. (Used in Moodle core.)

---

## 1. Symfony Routing

**Definition file options**:

* **YAML** (`config/routes.yaml`)

  ```yaml
  blog_show:
    path: /blog/{slug}
    controller: App\Controller\BlogController::show
    requirements:
      slug: "[a-z0-9\-]+"
  ```

* **PHP 8 Attributes** (in controller)

  ```php
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\HttpFoundation\Response;

  class BlogController
  {
      #[Route(
          path: '/blog/{slug}',
          name: 'blog_show',
          requirements: ['slug' => '[a-z0-9\-]+'],
          methods: ['GET']
      )]
      public function show(string $slug): Response
      {
          // ... your code ...
      }
  }
  ```

* **PHP** (`config/routes.php`)

  ```php
  use Symfony\Component\Routing\Route;
  use Symfony\Component\Routing\RouteCollection;

  $routes = new RouteCollection();
  $routes->add('blog_show', new Route(
      '/blog/{slug}',
      ['_controller' => 'App\\Controller\\BlogController::show'],
      ['slug' => '[a-z0-9\\-]+'],
      methods: ['GET']
  ));

  return $routes;
  ```

Key points:

* Routes can be defined via YAML, PHP 8 attributes, or PHP code.
* Define HTTP methods, requirements, host/scheme if needed.


**Testing it !**:

Go to frameworks/symfony_project and run
```bash
  php -S localhost:8000 -t public
```

---

## 2. Laravel Routing

**Basic routes** (in `routes/web.php`):

```php
Route::get('/posts/{id}', [PostController::class, 'show']);

// Closure route\ nRoute::get('/greet/{name}', function (string $name) {
    return "Hello, $name!";
});
```

**Route parameters & constraints**:

```php
Route::get('/user/{id}', [UserController::class, 'profile'])
     ->where('id', '[0-9]+');
```

**Route groups & middleware**:

```php
Route::prefix('admin')
     ->middleware('auth')
     ->group(function () {
         Route::get('/dashboard', [DashboardController::class, 'index']);
     });
```

Key points:

* Fluent, expressive syntax.
* Implicit model binding.
* Name routes with `->name('route.name')`.

---

## 3. Slim Framework

**Setup & basic route**:

```php
$app = \Slim\Factory\AppFactory::create();

$app->get('/hello/{name}', function (
    \Psr\Http\Message\ServerRequestInterface $request,
    \Psr\Http\Message\ResponseInterface $response,
    array $args
) {
    $response->getBody()->write("Hello, {$args['name']}");
    return $response;
});

$app->run();
```

**Key features**:

* PSR-7 request/response.
* Middleware support.
* Grouped routes with `$app->group()`.

**Testing it !**: 

Go to frameworks/slim_framework_project and run
```bash
  php -S localhost:8000 -t public
```

---

## 4. FastRoute

**Definition & dispatcher**:

```php
use FastRoute\Dispatcher;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users/{id:\\d+}', 'get_user_handler');
    $r->addRoute('POST', '/users', 'create_user_handler');
});

// Retrieve HTTP method & URI
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        // handle 404
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        // handle 405
        break;
    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        call_user_func($handler, $vars);
        break;
}
```

Key points:

* High-performance dispatcher.
* Pure PHP definition—no additional layers.

**Testing it !**:

Go to frameworks/fast_route_project and run
```bash
  php -S localhost:8000 -t public
```

---

## Next Steps

In the next module, **Road Signs and Rules**, we’ll dive into route patterns, parameter placeholders, and constraints—setting you up to define your own Moodle routes confidently.
