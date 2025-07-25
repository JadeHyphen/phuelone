<?php

// -------------------------------------------------------
// Autoload dependencies and core classes
// -------------------------------------------------------
require __DIR__ . '/../vendor/autoload.php';

// -------------------------------------------------------
// Load environment variables (if using)
// -------------------------------------------------------
use Core\Support\Env;
Env::load(__DIR__ . '/../.env');

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
// Generate CSRF token for forms
// -------------------------------------------------------
use App\Http\Middleware\CsrfMiddleware;
$csrfToken = CsrfMiddleware::generateToken();

// -------------------------------------------------------
// Dispatch the router and send the response
// -------------------------------------------------------
$response = $router->dispatch($request);
$response->send();

?>