<?php

namespace Core\Http\Middleware;

use Core\Http\Request;
use Core\Http\Response;

class Authenticate extends Middleware
{
    public function handle(Request $request, \Closure $next): mixed
    {
        if (!isset($_SESSION['user'])) {
            $response = new Response();
            $response->redirect('/login');
            return $response;
        }

        return $next($request);
    }
}

?>