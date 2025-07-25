<?php

namespace Core\Queue;

use Predis\Client;

class RedisQueue
{
    protected $client;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/redis.php';
        $this->client = new Client($config['queue']);
    }

    public function push($queue, $data)
    {
        $this->client->lpush($queue, json_encode($data));
    }

    public function pop($queue)
    {
        $data = $this->client->rpop($queue);
        return $data ? json_decode($data, true) : null;
    }
}
