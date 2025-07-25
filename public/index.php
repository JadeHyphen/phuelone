<?php

// -------------------------------------------------------
// Autoload dependencies and core classes
// -------------------------------------------------------
require __DIR__ . '/../vendor/autoload.php';

// -------------------------------------------------------
// Load environment variables (if using)
// -------------------------------------------------------
use Core\Support\Env;
Env::load(__DIR__ . '/../');

// -------------------------------------------------------
// Bootstrap core services
// -------------------------------------------------------
use Core\Http\Request;
use Core\Http\Router;

// -------------------------------------------------------
// Instantiate Request and Router
// -------------------------------------------------------
$request = new Request();
$router = require __DIR__ . '/../routes/web.php'; // We'll define this next

// -------------------------------------------------------
// Dispatch the router and send the response
// -------------------------------------------------------
$response = $router->dispatch($request);
$response->send();

?>