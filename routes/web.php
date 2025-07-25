<?php

use Core\Http\Router;
use App\Http\Controllers\HomeController;
use Core\Http\Middleware\Authenticate;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/dashboard', [HomeController::class, 'dashboard'])->middleware([
    Authenticate::class
]);

return $router;

?>