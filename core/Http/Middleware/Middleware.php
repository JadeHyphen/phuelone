<?php

namespace Core\Http\Middleware;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Class Middleware
 *
 * Base middleware class. Middleware can modify the request/response or stop the chain.
 */
abstract class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    abstract public function handle(Request $request, \Closure $next): mixed;
}

?>