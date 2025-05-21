# 04 – Bridges & Shortcuts: Advanced Routing in Moodle

In this module, we’ll connect your routes to controller logic and explore advanced techniques like middleware, nested routes, and completely overriding the core router using Moodle’s DI system.

---

## 1. Connecting Routes to Controllers

Once you declare a `#[route]` attribute on a class or method, Moodle will automatically dispatch matching URLs to your handler. Under the hood, the `route_controller` trait:

* Parses URL segments and query/header parameters
* Invokes your method with typed arguments
* Handles response creation (HTML or JSON)

Example:

```php
class report_controller {
    use \core\router\route_controller;
    #[route(
      path: '/report/{id}',
      method: ['GET'],
      title: 'View report',
      pathtypes: [new \core\router\schema\parameters\path_parameter(
          name: 'id', type: param::INT
      )]
    )]
    public function view(\Psr\Http\Message\ServerRequestInterface $request,
                         int $id): \Psr\Http\Message\ResponseInterface {
        // build page with $id
    }
}
```

## 2. Security and login

Moodle’s router can enforce security checks using the `requirelogin` parameter. This ensures users are authenticated before accessing certain routes.


  ```php
  #[route(
      path: '/secure',
      requirelogin: new require_login(
          requirelogin: true,
          courseattributename: 'course',
      ),
  )]
  ```
* **`requirelogin`**: Enforces user authentication. If not logged in, redirects to the login page.


---

## 3. Middleware

Moodle’s router supports middleware stacks via the `middleware` parameter on attributes or in core DI definitions. 
Middleware can inspect or modify the PSR-7 request/response lifecycle before and after your handler. 

As of now the router will apply the middleware depending on the type of route (API or Controller or shim). This is added in the core router class directly
when creating the route. So far I have not seen an easy way to add middleware to a particular route.

A good definition here [https://www.slimframework.com/docs/v4/concepts/middleware.html#how-does-middleware-work]:

`Middleware is a layer that sits between the client request and the server response in a web application. 
It intercepts, processes, and potentially alters HTTP requests and responses as they pass through the application pipeline.
...
Each middleware performs its function and then passes control to the next middleware in the chain, enabling a modular and reusable approach to handling cross-cutting concerns in web applications.`

---

## Summary

* **Routes → Controllers**: Use `#[route]` attributes and the `route_controller` trait to wire URLs to handlers.
* **Middleware**: Moodle applies middleware at route or group level for auth, logging, etc.

Next up: **Don’t Get Lost!** — debugging routing issues, tracing paths and testing.
