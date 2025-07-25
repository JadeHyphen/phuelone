<?php

namespace Core\Search;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Class SearchMiddleware
 *
 * Middleware to log search queries.
 */
class SearchMiddleware
{
    public function handle(Request $request, \Closure $next): Response
    {
        $query = $request->query('q', '');
        if ($query) {
            // Log the search query (replace with actual logging logic)
            file_put_contents('search.log', $query . PHP_EOL, FILE_APPEND);
        }

        return $next($request);
    }
}

?>
