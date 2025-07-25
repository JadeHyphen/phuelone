<?php

namespace Core\Search;

/**
 * Class SearchEngine
 *
 * Handles full-text search and filtering.
 */
class SearchEngine
{
    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function search(string $query): array
    {
        return array_filter($this->data, function ($item) use ($query) {
            return stripos($item, $query) !== false;
        });
    }

    public function filter(array $criteria): array
    {
        return array_filter($this->data, function ($item) use ($criteria) {
            foreach ($criteria as $key => $value) {
                if (!isset($item[$key]) || $item[$key] !== $value) {
                    return false;
                }
            }
            return true;
        });
    }
}

?>
