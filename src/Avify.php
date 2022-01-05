<?php

declare(strict_types=1);

namespace App;

use App\Utils\Curl;
use App\Utils\Crypt;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 1), '.env');
$dotenv->load();
class Avify
{
    private $baseURL;
    private $mode;
    private $publicKey;

    public function __construct(string $mode)
    {
        $this->baseURL = $mode == 'sandbox' ? 'https://sandboxapi.avify.co' : 'https://api.avify.co';
        $this->mode = $mode;
        $this->publicKey = $this->getPaymentsPubliKey();
    }

    function setBaseURL(string $baseURL)
    {
        $this->baseURL = $baseURL;
    }

    function getBaseURL()
    {
        return $this->baseURL;
    }

    function setMode(string $mode)
    {
        $this->mode = $mode;
    }

    function getMode()
    {
        return $this->mode;
    }

    function setPublicKey(string $publicKey)
    {
        $this->publicKey = $publicKey;
    }

    function getPublicKey()
    {
        return $this->publicKey;
    }

    function getPaymentsPubliKey(string $mode = 'sandbox', string $version = 'v1')
    {
        $headers =  array("api-key: {$_ENV['API_KEY']}");
        $baseURL = $mode == 'sandbox' ? 'https://sandboxapi.avify.co' : 'https://api.avify.co';
        $url = "{$baseURL}/api/{$version}/integrations/payments/key";
        $response = Curl::get($url, $headers);
        return $response['key'];
    }

    public function checkout(array $card, string $mode = 'sandbox', string $version = 'v1')
    {
        // This passphrase is used for testing purposes only.
        // The key will be part of the request.
        // TODO: remove this randomPassphrase.
        $randomPassphrase = base64_encode(openssl_random_pseudo_bytes(16));
        $encryptedCard = Crypt::encrypt(json_encode($card), $randomPassphrase);

        $headers =  array("api-key: {$_ENV['API_KEY']}", 'Content-Type: application/json');
        $baseURL = $mode == 'sandbox' ? 'https://sandboxapi.avify.co' : 'https://api.avify.co';
        $url = "{$baseURL}/api/{$version}/integrations/payments/checkout";

        $encryptedKey = Crypt::encryptWithPublicKey($randomPassphrase, $this->publicKey);
        $json = json_encode(array(
            'storeId' => 25, // TODO: remove this raw ID.
            'data' => $encryptedCard,
            'key' => $encryptedKey
        ));

        $response = Curl::post($url, $headers, $json);
        $message = $this->getFormattedCheckoutResponse($response);
        return $message;
    }

    private function getFormattedCheckoutResponse($response)
    {
        if ($response) {
            $status = $response['status'];
            $message = [
                'success' => $status === 200,
                'httpCode' => $status
            ];

            if ($status === 200) {
                $paymentInfo = $response['payment'];
                $message['data'] = $paymentInfo;
            } else {
                $error = $response['error'];
                $message['error'] = [
                    'message' => $error['displayMessage'],
                    'code' => $error['code'],
                ];
            }
            return $message;
        }
    }
}
