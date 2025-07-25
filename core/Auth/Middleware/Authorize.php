<?php

namespace Core\Auth\Middleware;

use Core\Http\Request;
use Core\Http\Response;
use Core\Auth\Auth;

/**
 * Class Authorize
 *
 * Middleware to ensure the user has the required role.
 */
class Authorize
{
    protected string $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function handle(Request $request, \Closure $next): Response
    {
        if (!Auth::authorize($this->role)) {
            $response = new Response('Forbidden', 403);
            return $response;
        }

        return $next($request);
    }
}

?>
