<?php

namespace Core\Session;

use Predis\Client;
use SessionHandlerInterface;

class RedisSessionHandler implements SessionHandlerInterface
{
    protected $client;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/redis.php';
        $this->client = new Client($config['default']);
    }

    public function open(string $savePath, string $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $sessionId): string
    {
        return $this->client->get($sessionId) ?: '';
    }

    public function write(string $sessionId, string $data): bool
    {
        $this->client->set($sessionId, $data);
        $this->client->expire($sessionId, 3600); // 1 hour TTL
        return true;
    }

    public function destroy(string $sessionId): bool
    {
        $this->client->del([$sessionId]);
        return true;
    }

    public function gc(int $maxLifetime): int|false
    {
        return true;
    }
}
