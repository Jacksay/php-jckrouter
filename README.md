php-jckrouter
=============

A very simple PHP routing classe (created for PHP students beginers)

Use
---
Include JckRouter `require_once 'path/to/JckRouter.php'`.

Configure routes using `JckRouter::addRoute("RouteName", "Pattern")` method.

```php
require_once 'lib/JckRouter.php';

// Add some routes
JckRouter::addRoute("index", "/");
JckRouter::addRoute("products", "/products.html");
JckRouter::addRoute("contacts", "/contacts.html");
JckRouter::addRoute("creator", "/creator-{id}.html");
```
Then, for get current route, just use `geRoute()` method : 

```php
// Return assoc array with route match with current URL
$requestedRoute = JckRouter::getRoute();
echo $requestedRoute['name'];
```
For generate URL from route name, use `getUrl(...)`

```php
// Display URL from Route name
echo JckRouter::getUrl('product.html');

// and with parameters
echo JckRouter::getUrl('creator', array( 'id' => 'Maurice' ));
```
