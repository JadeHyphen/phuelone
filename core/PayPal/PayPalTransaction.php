<?php

namespace Core\PayPal;

use Core\Providers\PayPalServiceProvider;

class PayPalTransaction
{
    protected $paypalServiceProvider;

    public function __construct()
    {
        $this->paypalServiceProvider = new PayPalServiceProvider();
    }

    public function createPayment($amountValue, $currency, $description, $returnUrl, $cancelUrl)
    {
        $accessToken = $this->paypalServiceProvider->getAccessToken();

        $url = "https://api-m.sandbox.paypal.com/v1/payments/payment";

        $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ];

        $body = json_encode([
            "intent" => "sale",
            "redirect_urls" => [
                "return_url" => $returnUrl,
                "cancel_url" => $cancelUrl
            ],
            "payer" => [
                "payment_method" => "paypal"
            ],
            "transactions" => [
                [
                    "amount" => [
                        "total" => $amountValue,
                        "currency" => $currency
                    ],
                    "description" => $description
                ]
            ]
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function executePayment($paymentId, $payerId)
    {
        $accessToken = $this->paypalServiceProvider->getAccessToken();

        $url = "https://api-m.sandbox.paypal.com/v1/payments/payment/$paymentId/execute";

        $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ];

        $body = json_encode([
            "payer_id" => $payerId
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
