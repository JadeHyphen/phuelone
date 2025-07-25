<?php

namespace Core\Search;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Class SearchController
 *
 * Handles HTTP requests for search functionality.
 */
class SearchController
{
    protected SearchEngine $searchEngine;

    public function __construct(SearchEngine $searchEngine)
    {
        $this->searchEngine = $searchEngine;
    }

    public function search(Request $request): Response
    {
        $query = $request->query('q', '');
        $results = $this->searchEngine->search($query);

        return new Response(json_encode($results), 200);
    }

    public function filter(Request $request): Response
    {
        $criteria = $request->post();
        $results = $this->searchEngine->filter($criteria);

        return new Response(json_encode($results), 200);
    }
}

?>
