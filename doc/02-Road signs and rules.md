# 02 – Road Signs and Rules: Moodle Routing Attributes

In this module, we’ll dissect the `#[route]` attribute syntax in Moodle, explain each parameter you can use, and distinguish between the three route types: **controller**, **api**, and **shim**.

---

## 1. Anatomy of a `#[route]` Attribute

Moodle uses PHP 8 attributes (`#[route(...)]`) to declare routes on controller classes or methods. A minimal example:

```php
#[route(
    path: '/preferences',
    method: ['GET'],
    title: 'Fetch user preferences',
    description: 'Fetch one user preference or all preferences',
    pathtypes: [ /* path parameters */ ],
    queryparams: [ /* query parameters */ ],
    headerparams: [ /* header parameters */ ],
    responses: [ /* response schemas */ ],
)]
class preferences {
    use \core\router\route_controller;
    
    // ... handler methods ...
}
```

### Attribute Parameters

| Parameter                                                                     | Type      | Description                                                 | Required? |
| ----------------------------------------------------------------------------- | --------- | ----------------------------------------------------------- | --------- |
| `path`                                                                        | string    | URL pattern. May include placeholders in `{}`.              | **Yes**   |
| `method`                                                                      | string\[] | HTTP methods (GET, POST, etc.). Defaults to GET.            | No        |
| `title`                                                                       | string    | Human-readable title for documentation.                     | No        |
| `description`                                                                 | string    | Description of the route’s behavior.                        | No        |
| `pathtypes`                                                                   | object\[] | Array of `path_parameter` or specialized parameter classes. | No        |
| `queryparams`                                                                 | object\[] | Array of `query_parameter` instances.                       | No        |
| `headerparams`                                                                | object\[] | Array of header parameter definitions.                      | No        |

#### Common Parameter Classes

* **`path_parameter`** (`name`, `type`, `default?`) — Defines a placeholder in the URL path.
* **`query_parameter`** (`name`, `type`, `default?`, `description?`) — Defines a GET/POST parameter.
* **`header_parameter`** (`name`, `type`, `description?`) — Defines an expected HTTP header.
* **`payload_response`**, **`json_media_type`**, etc. — Define response shapes and HTTP status codes.


## 2. Controller Routes

Controller routes live under `namespace core\route\controller;` and automatically render Moodle pages.

```php
namespace core\route\controller;

use core\router;
use core\router\route;
use core\router\schema\parameters\query_parameter;

#[route(
    path: '/error',
    method: ['GET','POST'],
    queryparams: [
        new query_parameter(
            name: 'code',
            type: \core\param::INT,
            default: 404,
        ),
    ],
)]
class page_not_found_controller {
    use \core\router\route_controller;

    public function page_not_found_handler(
        \Psr\Http\Message\ServerRequestInterface $request
    ): \Psr\Http\Message\ResponseInterface {
        // ... render page ...
    }
}
```

* **Purpose**: Map URLs to page controllers that build and output HTML using Moodle’s `$PAGE` and `$OUTPUT`.
* **Usage**: Use `queryparams` for URL query data, and rely on `route_controller` trait to handle response boilerplate.

---

## 3. API Routes

API routes live under `namespace core\route\api;` and expose JSON or payload responses.

```php
namespace core\route\api;

use core\router\route;
use core\router\schema\response\payload_response;
use core\router\schema\parameters\path_parameter;

#[route(
    path: '/templates/{themename}/{component}/{identifier:.*}',
    method: ['GET'],
    title: 'Fetch a single template',
    description: 'Return template content and strings as JSON',
    pathtypes: [
        new path_parameter(name: 'identifier', type: \core\param::SAFEPATH),
    ],
    queryparams: [/* includecomments param */],
    responses: [
        new \core\router\schema\response\response(
            statuscode: 200,
            description: 'OK',
            content: [ /* JSON schema */ ],
        ),
    ],
)]
class templates {
    use \core\router\route_controller;

    public function get_templates(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response,
        string $themename,
        string $component,
        string $identifier
    ): payload_response {
        // ... return JSON payload_response ...
    }
}
```

* **Purpose**: Serve RESTful endpoints that return structured data (JSON, XML).
* **Key difference**: Use `responses` and often `payload_response` instead of rendering Moodle pages.

---

## 4. Shim Routes

Shim routes exist under `namespace core\route\shim;` to redirect legacy URLs to new controllers.

```php
namespace core\route\shim;

use core\router\route;
use core\router\schema\parameters\query_parameter;

#[route(
    path: '/error[/index.php]',
    queryparams: [
        new query_parameter(name: 'code', type: \core\param::INT),
    ],
)]
class error_controller {
    public function administer_course(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ) {
        return \core\router\util::redirect_to_callable(
            $request, $response,
            [\core\route\controller\page_not_found_controller::class, 'page_not_found_handler']
        );
    }
}
```

* **Purpose**: Handle old URL formats or `index.php` calls and forward them to the new controller or API route.
* **Behavior**: Typically use `util::redirect_to_callable` or `util::redirect`.

**Shim Setup**: To enable shim routes, replace the content of the original legacy PHP file (e.g., `error/index.php`) with:
```php
<?php
 require_once(__DIR__ . "/../../r.php");
die();
```
This ensures legacy URLs are delegated to the Moodle routing system. array           | Response schema or status codes to return.     | No        |

---

## 5. Summary

| Route Type | Namespace               | Output       | Use Case                 |
| ---------- | ----------------------- | ------------ | ------------------------ |
| Controller | `core\route\controller` | HTML pages   | User-facing pages        |
| API        | `core\route\api`        | JSON/Payload | RESTful data endpoints   |
| Shim       | `core\route\shim`       | Redirect     | Legacy URL compatibility |

With this understanding of attributes and route types, you’re ready to **define patterns**, **declare constraints**, and **document your Moodle endpoints** clearly.

---

Next up: **Creating Your Own Routes**, where we'll build a plugin from scratch and register custom routes.
