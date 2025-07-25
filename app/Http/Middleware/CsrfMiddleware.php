<?php

namespace App\Http\Middleware;

use Core\Http\Request;
use Core\Http\Response;

class CsrfMiddleware
{
    public function handle(Request $request, callable $next): Response
    {
        if ($request->method() === 'POST') {
            $token = $request->input('_csrf_token');
            $sessionToken = $_SESSION['_csrf_token'] ?? null;

            if (!$token || $token !== $sessionToken) {
                return new Response('CSRF token mismatch', 403);
            }
        }

        return $next($request);
    }

    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['_csrf_token'] = $token;
        return $token;
    }
}
