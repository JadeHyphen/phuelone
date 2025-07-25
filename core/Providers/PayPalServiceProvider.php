<?php

namespace Core\Providers;

class PayPalServiceProvider
{
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/paypal.php';

        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
    }

    public function getAccessToken()
    {
        $url = "https://api-m.sandbox.paypal.com/v1/oauth2/token";

        $headers = [
            "Authorization: Basic " . base64_encode("{$this->clientId}:{$this->clientSecret}"),
            "Content-Type: application/x-www-form-urlencoded"
        ];

        $data = "grant_type=client_credentials";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true)['access_token'] ?? null;
    }
}
