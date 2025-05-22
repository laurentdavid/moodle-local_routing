# Moodle Local Router Plugin

A minimalist example showing how to wire up Moodle’s PHP-8 router in a `local/` plugin.

## Features

* Installs like any other local plugin.
* Demonstrates how to use the new routing system in Moodle 4.5+.
* Provides simple examples of a controller.

---

## Quick Start

1. **Clone** into your Moodle tree:

   ```bash
   cd /path/to/moodle/local
   git clone https://github.com/laurentdavid/moodle-local_routing.git router
   ```
2. **Install** in Moodle via **Site administration ▶️ Notifications**.
3. *(Optional)* Enable “clean” URLs with Apache:

Either copy the .htaccess file from the plugin root to your Moodle root or create
a new `.htaccess` file in your Moodle root with the following content:

   ```bash
   ```apacheconf
   # in your Moodle root or local/router/.htaccess
   FallbackResource /r.php
   ```

   And ensure `AllowOverride All` in your original `<Directory>` block.

---

## Explore the API

* **Swagger UI**: `https://<your-site>/admin/swaggerui.php`
* **OpenAPI JSON**: `https://<your-site>/r.php/api/rest/v2/openapi.json`

---

## Try It Out

* **Test JSON controller**:
  `https://<your-site>/local_routing/sample/world`
  You should get a simple `Hello, world!`-style response.

---

## Learn More

All the installation steps, routing details, and shim setup live in **Chapter 3** of this training:
**03 – Creating Your Own Routes**.
👉 See **03-Creating-Your-Own-Routes.md** for the full walkthrough.
