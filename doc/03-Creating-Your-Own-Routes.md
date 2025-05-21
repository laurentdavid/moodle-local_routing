# 03 – Creating Your Own Routes: Moodle Local Router Plugin

In this module, we’ll build a simple local plugin that demonstrates how to register and use custom routes in Moodle. You’ll learn how to install the plugin, configure your web server for routing, explore built‑in API documentation, and create your own controller to handle a custom route.

---

## Plugin Overview

**Moodle Local Router Plugin** is a tutorial plugin illustrating:

* Standard Moodle installation process for local plugins.
* Apache fallback configuration for clean URLs.
* Sample controller with a custom route.
* Built‑in Swagger/OpenAPI endpoints for API exploration.

---

## 1. Installation

1. Clone the plugin into your Moodle installation under `local/router`:

   ```bash
   cd /path/to/moodle/local
   git clone https://github.com/laurentdavid/moodle-local_routing.git router
   ```
2. In Moodle, navigate to **Site administration > Notifications** to trigger installation.
3. Follow on‑screen prompts to complete setup.

---

## 2. (Optional) Web Server Routing Setup

For clean URLs without `r.php`, configure Apache:

1. Copy the `.htaccess` from the plugin root into your Moodle directory or `/local/router`:

   ```apacheconf
   FallbackResource /local/router/r.php
   ```
2. Ensure Apache allows `.htaccess` overrides:

   ```apacheconf
   <Directory /path/to/moodle>
     AllowOverride All
     Options FollowSymLinks
     Require all granted
   </Directory>
   ```
3. Reload Apache:

   ```bash
   sudo service apache2 reload
   ```

> Refer to [Moodle Docs: Configuring the Router](https://docs.moodle.org/405/en/Configuring_the_Router) for more.

---

## 3. Exploring Built‑In API Documentation

Once installed, you can inspect the plugin’s API definitions:

* **Swagger UI**: `https://<your-site>/admin/swaggerui.php`
* **OpenAPI JSON**: `https://<your-site>/r.php/api/rest/v2/openapi.json`

These endpoints provide interactive documentation and machine‑readable schemas.

---

## 4. Checking Current URL Formats

With MDL‑82565, Moodle reliably parses incoming URLs. Test with:

```
https://<your-site>/r.php/course/1/manage
```

You should see a JSON response showing parsed path segments and query parameters. With Apache fallback, you can omit `r.php`:

```
https://<your-site>/course/1/manage
```

---

## 5. Testing the Sample Controller

The plugin includes a `sample` controller demonstrating a basic route:

1. Ensure plugin is installed and routing configured.
2. Visit:

   ```
   https://<your-site>/local_routing/sample
   ```
3. You should receive a JSON response from the `sample` handler.

---

## 6. Route Types in `route_loader.php`

Moodle’s core `route_loader.php` defines three route types:

| Type           | Definition                                         | Use Case                            |
| -------------- | -------------------------------------------------- | ----------------------------------- |
| **API**        | Routes matching `/r.php/api/...` with HTTP methods | RESTful endpoints                   |
| **Controller** | Routes managed by a controller class/method        | Custom page controllers             |
| **Shim**       | Catch-all or legacy URL redirections               | Backward compatibility for old URLs |

**Shim Setup Reminder**: For shim routes to work, replace the legacy entry file (e.g., `local/router/index.php`) with:

```php
<?php
require_once(__DIR__ . '/../../r.php');
die();
```

---

## Next Steps

In the next module, **Bridges & Shortcuts**, we’ll connect your routes to controller logic and explore advanced use cases like middleware and nested routes.
