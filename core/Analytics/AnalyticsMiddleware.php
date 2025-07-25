<?php

namespace Core\Analytics;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Closure;

/**
 * Class AnalyticsMiddleware
 *
 * Logs analytics data during user interactions.
 */
class AnalyticsMiddleware extends Middleware
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function handle(Request $request, Closure $next): mixed
    {
        // Log user interaction
        $this->analyticsService->collect('user_interaction', 1);

        return $next($request);
    }
}

?>
