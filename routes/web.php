<?php

use Core\Http\Router;
use App\Http\Controllers\HomeController;
use Core\Http\Middleware\Authenticate;
use Core\Analytics\AnalyticsController;
use App\Http\Middleware\InputValidationMiddleware;
use App\Http\Middleware\AuthenticationMiddleware;
use App\Http\Middleware\AuthorizationMiddleware;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/dashboard', [HomeController::class, 'dashboard'])->middleware([
    Authenticate::class,
    AuthenticationMiddleware::class
]);

// Admin dashboard route
$router->get('/admin/dashboard', function() {
    require __DIR__ . '/../app/Views/admin_dashboard.php';
})->middleware([
    AuthenticationMiddleware::class,
    AuthorizationMiddleware::class
])->attribute('required_role', 'admin');

// Analytics report endpoint
$router->get('/admin/analytics/report', [AnalyticsController::class, 'generateDashboardReport'])->middleware([
    AuthenticationMiddleware::class,
    AuthorizationMiddleware::class
])->attribute('required_role', 'admin');

// Fallback route for undefined routes
$router->fallback(function() {
    echo 'Page not found';
});

return $router;

?>