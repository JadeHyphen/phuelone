<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/sentry.php';

\Sentry\init([
    'dsn' => $config['dsn'],
    'environment' => $config['environment'],
    'release' => $config['release'],
    'send_default_pii' => $config['send_default_pii'],
]);

// ...existing code...