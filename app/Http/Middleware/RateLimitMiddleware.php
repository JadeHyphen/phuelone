<?php

namespace App\Http\Middleware;

use Closure;
use Core\Cache\RedisCache;
use Core\Http\Request;
use Core\Http\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cache = new RedisCache();
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = "rate_limit_{$ip}";
        $maxAttempts = 100; // Max requests per minute

        $attempts = $cache->get($key) ?? 0;

        if ($attempts >= $maxAttempts) {
            return new Response("Too many requests. Please try again later.", 429);
        }

        $cache->set($key, $attempts + 1, 60); // Cache for 60 seconds

        return $next($request);
    }
}
