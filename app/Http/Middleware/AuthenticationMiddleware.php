<?php

namespace App\Http\Middleware;

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Session;

class AuthenticationMiddleware
{
    public function handle(Request $request, callable $next)
    {
        $session = $request->session(); // Use session() method from Request class

        // Ensure session is initialized
        if (!$session->has('user_authenticated')) {
            return new Response('Unauthorized access.', 401);
        }

        return $next($request);
    }
}
