<?php

namespace App\Http\Middleware;

use Closure;
use Core\Http\Request;
use Core\Http\Response;

class RbacMiddleware
{
    private array $rolesPermissions;

    public function __construct()
    {
        $this->rolesPermissions = require __DIR__ . '/../../config/roles.php';
    }

    public function handle(Request $request, Closure $next): Response
    {
        $userRole = $request->getUserRole();
        $route = $request->getRoute();

        if (!isset($this->rolesPermissions[$userRole]) || !in_array($route, $this->rolesPermissions[$userRole])) {
            return new Response("Access Denied", 403);
        }

        return $next($request);
    }
}
