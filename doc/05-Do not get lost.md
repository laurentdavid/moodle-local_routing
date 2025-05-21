# 05 – Don’t Get Lost!: Debugging and Testing Moodle Routes

In this module, we’ll cover techniques to trace, debug, and test your Moodle routing definitions to ensure your URLs land in the right place.

---

## 1. Tracing Route Resolution

When a request arrives, Moodle’s router:

1. Loads all `#[route]` attributes and core route definitions.
2. Matches the incoming path and HTTP method against registered routes.
3. Extracts parameters according to `pathtypes`, `queryparams`, and `headerparams`.
4. Dispatches to your controller via the `route_controller` trait or shim.

**Debug Tips**:

* Enable full debugging in `config.php`:

  ```php
  @error_reporting(E_ALL);
  $CFG->debug = (E_ALL | E_STRICT);
  $CFG->debugdisplay = 1;
  ```
* Dump all routes at runtime:

  ```php
  $router = \core\di::get(\core\router::class);
  foreach ($router->get_app()->getRouteCollector()->getRoutes() as $route) {
    echo html_writer::tag('h2', $route->getPattern());
    echo html_writer::tag('pre', json_encode($route->getName()));
    echo html_writer::tag('pre', json_encode($route->getPattern()));
  }
  ```
---

## 2. Common Pitfalls and Fixes

| Issue                          | Symptom                    | Fix                                                        |
| ------------------------------ | -------------------------- | ---------------------------------------------------------- |
| Incorrect `path` pattern       | 404 Not Found              | Verify URL structure and placeholder syntax.               |
| Type mismatches                | Exceptions or silent fail  | Check `pathtypes` and PHP types match.                     |
| Legacy files not shimmed       | Raw PHP output, 200 status | Ensure shim file does `require_once '../../r.php'; die();` |
| Stale cache                    | Changes not reflected      | Run `php admin/cli/purge_caches.php`.                      |

---

## 3. Automated Route Testing

Moodle 5.0+ provides PHPUnit tests for the router subsystem. Core tests are in **`lib/tests/router/route_controller_test.php`**, which:

* Loads all annotated routes.
* Builds default URLs and methods.
* Serves each request and asserts a PSR-7 response.

### Plugin Route Tests

In your plugin (e.g., `local/yourplugin/tests/router_test.php`):

```php
class yourplugin_route_test extends \advanced_testcase {
    public function test_sample_route() {
        $this->resetAfterTest();
        $this->setAdminUser(); // Set the user to admin if not we are redirected to the login page.
        $request = $this->create_request('GET', 'local_routing/sample/hello', route_loader_interface::ROUTE_GROUP_PAGE);
        $response = $this->get_app()->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('hello', (string) $response->getBody());
    }
}
```

For more details, refer to the core test documentation: [https://moodledev.io/docs/5.0/apis/subsystems/routing/testing)

---

