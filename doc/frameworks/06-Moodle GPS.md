# 06 – Moodle GPS: Real-World Examples

In this final module, we’ll navigate through real Moodle core subsystems to see routing in action. We’ll inspect how routes are declared in core code, follow the dispatch path, and learn how to locate these examples on the official GitHub repository.

---

## 1. Core API: Template Endpoints

The **Template API** exposes template assets for themes via a route in `core\route\api\templates`. You can explore the source here:

[https://github.com/moodle/moodle/blob/main/lib/route/api/templates.php](https://github.com/moodle/moodle/blob/main/lib/route/api/templates.php)

```php
#[route(
    path: '/templates/{themename}/{component}/{identifier:.*}',
    method: ['GET'],
    title: 'Fetch a single template',
    description: 'Return template content and strings as JSON',
    pathtypes: [
        new path_parameter(name: 'identifier', type: param::SAFEPATH),
    ],
    responses: [ /* ... */ ],
)]
class templates {
    use \core\router\route_controller;
    public function get_templates( /* ... */ ): payload_response {
        // loads template dependencies and returns payload
    }
}
```

* **File Location:** `lib/route/api/templates.php`
* **Dispatches to:** `get_templates()` returning a `payload_response`.

---

## 2. Core Controller: Page Not Found

The **404 handler** is implemented as a controller route. See:

[https://github.com/moodle/moodle/blob/main/lib/route/controller/page\_not\_found\_controller.php](https://github.com/moodle/moodle/blob/main/lib/route/controller/page_not_found_controller.php)

```php
#[route(
    path: '/error',
    method: ['GET','POST'],
    queryparams: [ new query_parameter(name: 'code', type: param::INT, default: 404) ],
)]
class page_not_found_controller {
    use \core\router\route_controller;
    public function page_not_found_handler(ServerRequestInterface $request): ResponseInterface {
        // builds $PAGE, $OUTPUT and returns a 404 response
    }
}
```

* **File Location:** `lib/route/controller/page_not_found_controller.php`
* **Key Points:** Handles both GET/POST, reads `code` queryparam, and renders Moodle UI.

---

## 3. Shim Example: Legacy URL Redirect

Legacy `/error/index.php` URLs are shims forwarding to the new controller. See:

[https://github.com/moodle/moodle/blob/main/lib/route/shim/error\_controller.php](https://github.com/moodle/moodle/blob/main/lib/route/shim/error_controller.php)

```php
#[route(
    path: '/error[/index.php]',
    queryparams: [ new query_parameter(name: 'code', type: param::INT) ],
)]
class error_controller {
    public function administer_course(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        return \core\router\util::redirect_to_callable(
            $request, $response,
            [page_not_found_controller::class, 'page_not_found_handler']
        );
    }
}
```

* **File Location:** `lib/route/shim/error_controller.php`
* **Behavior:** Catches old URLs and redirects via `redirect_to_callable`.

---

## 4. Finding More Routes on GitHub

To explore other core routes, browse the `lib/route` directory of the Moodle repo:

[https://github.com/moodle/moodle/tree/main/lib/route](https://github.com/moodle/moodle/tree/main/lib/route)

Here you’ll find separate folders for `api`, `controller`, and `shim`, each containing real-world route definitions you can study and adapt.

---

With these examples, you should now feel confident reading and tracing routing definitions throughout Moodle’s codebase. Thank you for following **Map It Out: The Art of Routing in Moodle** — happy routing!\*\*
