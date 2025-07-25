<?php

namespace Core\Cache;

use Throwable;
use Predis\Client;

class RedisCache
{
    protected $client;

    public function __construct()
    {
        try {
            echo "Loading Redis configuration...\n";
            $config = require __DIR__ . '/../../config/redis.php';
            print_r($config['cache']);
            echo "Redis configuration loaded.\n";

            echo "Initializing Predis\Client...\n";
            $this->client = new Client($config['cache']);
            echo "Predis\Client initialized successfully.\n";
        } catch (Throwable $e) {
            echo "Error in RedisCache constructor: " . $e->getMessage() . "\n";
        }
    }

    public function set($key, $value, $ttl = null)
    {
        $this->client->set($key, $value);
        if ($ttl) {
            $this->client->expire($key, $ttl);
        }
    }

    public function get($key)
    {
        return $this->client->get($key);
    }

    public function delete($key)
    {
        $this->client->del([$key]);
    }
}
