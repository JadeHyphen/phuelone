<?php

return [
    'url' => getenv('CDN_URL') ?: 'https://cdn.example.com',
    'assets' => [
        'css' => '/css/',
        'js' => '/js/',
        'images' => '/images/',
    ],
];
