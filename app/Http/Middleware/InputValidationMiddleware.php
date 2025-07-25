<?php

namespace App\Http\Middleware;

use Core\Http\Request;
use Core\Http\Response;

class InputValidationMiddleware
{
    public function handle(Request $request, callable $next)
    {
        $input = $request->input(); // Use input() method from Request class
        $sanitizedInput = [
            'name' => filter_var($input['name'] ?? '', FILTER_SANITIZE_STRING),
            'email' => filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL),
            'message' => filter_var($input['message'] ?? '', FILTER_SANITIZE_STRING),
        ];

        if (!$sanitizedInput['email']) {
            return new Response('Invalid email address.', 400);
        }

        // Update request data with sanitized input
        $request->setRouteAttributes($sanitizedInput); // Use setRouteAttributes for compatibility

        return $next($request);
    }
}
