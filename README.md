# Moodle Local Router Plugin

A sample Moodle plugin demonstrating how to use the Router component in a local plugin context. This project serves as a tutorial/training example for setting up and exploring custom routing in Moodle.

## Features

* Easy installation via Moodle's standard plugin installation process.
* Sample Apache `.htaccess` and `FallbackResource`.
* Demonstrates checking current URL formats (post MDL-82565).
* Includes a sample controller accessible via a simple route.

## 1. Installation

1. Download or clone this repository into your Moodle installation under `local/router`:

   ```bash
   cd /path/to/moodle/local
   git clone https://github.com/yourusername/moodle-local-router.git router
   ```

2. In your Moodle site administration, go to **Site administration > Notifications** to trigger the plugin installation.

3. Follow the on-screen prompts to complete the installation.

## 2. (Optional) Routing Setup

By default, Moodle handles plugin entry points. To enable custom routing via Apache:

1. Copy the `.htaccess` file in to your Moodle root (or the `local/router` directory) with the following content:

   ```apacheconf
   FallbackResource /local/router/r.php
   ```
Your global Apache conf should have a `Directory` directive that allows `.htaccess` files to be read:

   ```apacheconf
    <Directory /path/to/moodle>
        AllowOverride All
        Options FollowSymLinks
        Require all granted
    </Directory>
   ```

3. Restart or reload Apache:

   ```bash
   sudo service apache2 reload
   ```
Refer to [Moodle Docs: Configuring the Router](https://docs.moodle.org/405/en/Configuring_the_Router) for more details.

## 3. Exploring Rest API Endpoints

With the plugin installed, you can explore the following URLs in your browser:

* **Swagger UI**: `https://<your-site>/admin/swaggerui.php`
* **OpenAPI JSON**: `https://<your-site>/r.php/api/rest/v2/openapi.json`

These endpoints provide interactive documentation and machine-readable API definitions.

## 4. Checking Current URL Format (MDL-82565)

As of [MDL-82565](https://tracker.moodle.org/browse/MDL-82565), the Router now reliably detects the current URL across diverse server environments. To verify this functionality, point your browser to:

```
https://<your-site>/r.php/course/1/manage
```

You should see a response displaying the parsed path segments and any relevant query parameters.

> **Tip:** If you’ve configured your web server’s fallback to `r.php`, you can access the same endpoint without the `r.php` prefix:
>
> ````
> https://<your-site>/course/1/manage
> ```## 5. Testing the Sample Controller
> ````

A sample controller is included for demonstration. To test it:

1. Ensure the plugin is installed and routing is configured (optional).

2. Navigate to:

   ```
   https://<your-site>/local_routing/sample
   ```

3. You should receive a simple JSON response from the `sample` controller.

---
## 5. Route Types in `route_loader.php`

Moodle’s `route_loader.php` (located at `lib/classes/router/route_loader.php` in the core codebase) defines three primary route types that determine how incoming URLs are matched and dispatched to controllers:

1. **Static Routes**

    * **Definition**: Routes with fixed, literal paths (no placeholders).
    * **Use Case**: Ideal for endpoints with known, unchanging URLs (e.g., `/admin/settings`).
    * **Example**:

      ```php
      $routes->add('swaggerui', new route('/admin/swaggerui.php', 'admin_swaggerui', 'GET'));
      ```

https://spec.openapis.org/oas/v3.1.0#schema-object-examples

2. **Parameterized Routes**

    * **Definition**: Routes containing placeholders for variable path segments, captured as parameters.
    * **Use Case**: When URLs include IDs or slugs (e.g., `/course/{id}/view`).
    * **Example**:

      ```php
      $routes->add('course_manage', new route('/r.php/course/{courseid}/manage', 'course_manage', 'GET'));
      ```

      Here, `{courseid}` is extracted and passed to the controller method.

3. **Wildcard/Fallback Routes**

    * **Definition**: Catch-all patterns that match any path under a base URL, often using `*` wildcards or the FallbackResource mechanism.
    * **Use Case**: Routing all unmatched requests to a single entry point (commonly `r.php`). Useful for SPAs or OpenAPI handling.
    * **Example**:

      ```php
      // In Apache: FallbackResource /local/router/r.php
      // In route loader:
      $routes->add('api', new route('/r.php/api/{path}', 'api_dispatch', ['GET', 'POST']));
      ```

Each route type offers distinct flexibility: static for simplicity, parameterized for dynamic segments, and wildcard for broad catch-all behaviors. Understanding these helps you define clear, maintainable routing for your Moodle plugins.

For more information and advanced usage, refer to the source code and inline documentation within the plugin directory. Feel free to contribute improvements or ask questions in the Moodle development forums.
