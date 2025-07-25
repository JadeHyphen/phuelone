<?php

namespace Core\Auth\Middleware;

use Core\Http\Request;
use Core\Http\Response;
use Core\Auth\Auth;

/**
 * Class Authenticate
 *
 * Middleware to ensure the user is authenticated.
 */
class Authenticate
{
    public function handle(Request $request, \Closure $next): Response
    {
        if (!Auth::check()) {
            $response = new Response();
            $response->redirect('/login');
            return $response;
        }

        return $next($request);
    }
}

?>
