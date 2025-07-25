<?php

namespace Core\Search;

/**
 * Class SearchDataProvider
 *
 * Provides data for the search engine.
 */
class SearchDataProvider
{
    public static function getData(): array
    {
        // Replace with actual data source (e.g., database query)
        return [
            ['id' => 1, 'title' => 'First Thread', 'content' => 'This is the first thread.'],
            ['id' => 2, 'title' => 'Second Thread', 'content' => 'This is the second thread.'],
            ['id' => 3, 'title' => 'Third Thread', 'content' => 'This is the third thread.'],
        ];
    }
}

?>
