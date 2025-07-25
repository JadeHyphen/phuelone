<?php

require __DIR__ . '/vendor/autoload.php';

use Core\Cache\RedisCache;

try {
    $cache = new RedisCache();
    echo "RedisCache class loaded successfully.";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
