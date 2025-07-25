<?php

return [
    'enabled' => getenv('LOAD_BALANCER_ENABLED') ?: false,
    'strategy' => getenv('LOAD_BALANCER_STRATEGY') ?: 'round_robin', // Options: round_robin, least_connections, ip_hash
    'nodes' => [
        [
            'host' => getenv('NODE_1_HOST') ?: '127.0.0.1',
            'port' => getenv('NODE_1_PORT') ?: 80,
        ],
        [
            'host' => getenv('NODE_2_HOST') ?: '127.0.0.1',
            'port' => getenv('NODE_2_PORT') ?: 80,
        ],
    ],
];
