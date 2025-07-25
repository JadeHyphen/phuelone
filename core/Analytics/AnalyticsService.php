<?php

namespace Core\Analytics;

/**
 * Class AnalyticsService
 *
 * Handles analytics data collection and reporting.
 */
class AnalyticsService
{
    protected array $data = [];

    public function collect(string $metric, int $value): void
    {
        $this->data[] = [
            'metric' => $metric,
            'value' => $value,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }

    public function getReport(): array
    {
        return $this->data;
    }
}

?>
