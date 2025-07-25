<?php

namespace Core\Security;

class Encryption
{
    private string $method = 'AES-256-CBC';

    public function encrypt(string $data, string $key): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
        $encrypted = openssl_encrypt($data, $this->method, $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public function decrypt(string $data, string $key): string
    {
        list($encryptedData, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encryptedData, $this->method, $key, 0, $iv);
    }
}
