<?php

return [
    'dsn' => getenv('SENTRY_DSN') ?: '',
    'environment' => getenv('APP_ENV') ?: 'production',
    'release' => getenv('APP_VERSION') ?: '1.0.0',
    'breadcrumbs' => true,
    'send_default_pii' => true,
];
