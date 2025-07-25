<?php

namespace Core\Analytics;

/**
 * Class AnalyticsModel
 *
 * Represents analytics data in the system.
 */
class AnalyticsModel
{
    protected int $id;
    protected string $metric;
    protected int $value;
    protected string $timestamp;

    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['id'] ?? 0;
        $this->metric = $attributes['metric'] ?? '';
        $this->value = $attributes['value'] ?? 0;
        $this->timestamp = $attributes['timestamp'] ?? date('Y-m-d H:i:s');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'metric' => $this->metric,
            'value' => $this->value,
            'timestamp' => $this->timestamp,
        ];
    }
}

?>
