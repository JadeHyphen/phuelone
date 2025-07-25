<?php

return [
    'default' => [
        'host' => getenv('REDIS_HOST') ?: '127.0.0.1',
        'port' => getenv('REDIS_PORT') ?: 6379,
        'password' => getenv('REDIS_PASSWORD') ?: null,
        'database' => getenv('REDIS_DB') ?: 0,
    ],

    'cache' => [
        'host' => getenv('REDIS_CACHE_HOST') ?: '127.0.0.1',
        'port' => getenv('REDIS_CACHE_PORT') ?: 6379,
        'password' => getenv('REDIS_CACHE_PASSWORD') ?: null,
        'database' => getenv('REDIS_CACHE_DB') ?: 1,
    ],

    'queue' => [
        'host' => getenv('REDIS_QUEUE_HOST') ?: '127.0.0.1',
        'port' => getenv('REDIS_QUEUE_PORT') ?: 6379,
        'password' => getenv('REDIS_QUEUE_PASSWORD') ?: null,
        'database' => getenv('REDIS_QUEUE_DB') ?: 2,
    ],
];
