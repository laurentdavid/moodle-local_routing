# 00 – Introduction to Routing in Moodle

Welcome to the first step in our *Map It Out: The Art of Routing in Moodle*. 
In this module, we'll explore the foundations of routing in Moodle, understand why it's essential, and see how it fits into the broader Moodle architecture.

## What You’ll Learn

* **What is routing?** A quick refresher on routing in web applications. How traditional PHP frameworks (like Laravel or Symfony) handle routes.
* **Moodle’s routing core concepts**: How Moodle implements and organizes routes under the hood.
* **Why routes matter**: The role of clean URLs, user experience, and plugin integration.

## Why Routing Matters in Moodle

Routing determines how URLs map to different pages and functions in Moodle. Understanding routing lets you:

1. Create clear, user-friendly URLs for your plugins.
2. Start to refactor the old "web service" style of routing into a more modern, RESTful approach.
3. Organize your code logically by separating URL definitions from controller logic.
3. Extend or override core Moodle functionality safely.
4. Troubleshoot broken or misdirected links.

## But Wait, When was this done in Moodle?

Moodle has a long history of routing, evolving from simple URL handling to a more sophisticated system. 
The introduction of the Slim Framework and FastRoute in Moodle 3.9 marked a significant shift towards modern routing practices (thanks @Andrew Lyons!).
This training will help you understand how to leverage these systems effectively.

It started with an epic about web services here : https://tracker.moodle.org/browse/MDL-76834

1. Initial implementation : https://tracker.moodle.org/browse/MDL-81031 (r.php, openapi.json...), sammple implementation of a controller for user preferences....
2. Add support for regular pages: https://tracker.moodle.org/browse/MDL-82565: course/id/manage
3. Improver error handling: https://tracker.moodle.org/browse/MDL-82565

## How This Training Works

We’ll build step-by-step:

1. **The "Magna Carta”** – Overview of routing in PHP.
2. **“Road Signs and Rules”** – Understand patterns and constraints.
2. **“Creating Your Own Routes”** – Define and register routes in your plugin.
3. **“Bridges & Shortcuts”** – Connect routes to controllers and explore advanced use cases.
4. **“Don’t Get Lost!”** – Debugging routing issues.
5. **“Moodle GPS: Real-World Examples”** – Walkthroughs of core Moodle subsystems.

> **Tip:** Have a local dev site ready to follow along and test each step.

---

Next up: **The Magna Carta** – Overview of routing in PHP.
