<?php

return [
    'client_id' => getenv('PAYPAL_CLIENT_ID') ?: '',
    'client_secret' => getenv('PAYPAL_CLIENT_SECRET') ?: '',
    'settings' => [
        'mode' => getenv('PAYPAL_MODE') ?: 'sandbox', // Available options: sandbox or live
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => __DIR__ . '/../storage/logs/paypal.log',
        'log.LogLevel' => 'DEBUG', // Available options: DEBUG, INFO, WARN, ERROR
    ],
];
