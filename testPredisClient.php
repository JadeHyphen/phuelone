<?php

require __DIR__ . '/vendor/autoload.php';

use Predis\Client;

try {
    $client = new Client();
    echo "Predis\Client class loaded successfully.";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
