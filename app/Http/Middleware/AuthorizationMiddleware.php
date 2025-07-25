<?php

namespace App\Http\Middleware;

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Session;

class AuthorizationMiddleware
{
    public function handle(Request $request, callable $next)
    {
        $session = $request->session(); // Use session() method from Request class
        $userRole = $session->get('user_role');
        $requiredRole = $request->getRouteAttribute('required_role'); // Use getRouteAttribute for compatibility

        if ($userRole !== $requiredRole) {
            return new Response('Forbidden access.', 403);
        }

        return $next($request);
    }
}
