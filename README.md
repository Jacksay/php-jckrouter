php-jckrouter
=============

A very simple PHP routing classe (created for PHP students beginers)

Use
---
Include JckRouter `require_once 'path/to/JckRouter.php'`.

Configure routes using `JckRouter::addRoute("RouteName", "Pattern")` method.

    require_once 'lib/JckRouter.php';

    // Add some routes
    JckRouter::addRoute("index", "/");
    JckRouter::addRoute("products", "/products.html");
    JckRouter::addRoute("products", "/contacts.html");
    JckRouter::addRoute("creator", "/creator-{id}.html");

    // Return assoc array with route match
    $requestedRoute = JckRouter::getRoute();