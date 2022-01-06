<?php

declare(strict_types=1);

namespace App;

use App\Utils\Curl;
use App\Utils\Crypt;
use Dotenv\Dotenv;
use App\Utils\Json;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 1), '.env');
$dotenv->load();
class Avify
{
    private $baseURL;
    private $mode;
    private $publicKey;
    private $prodBaseUrl = 'https://api.avify.co';
    private $sandboxBaseUrl = 'https://sandboxapi.avify.co';

    public function __construct(string $mode, string $version)
    {
        $this->baseURL = ($mode == 'sandbox' ? $this->sandboxBaseUrl : $this->prodBaseUrl) . '/api/' . $version;
        $this->mode = $mode;
        $this->publicKey = $this->getPaymentsPublicKey();
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

    public function getPaymentsPublicKey()
    {
        $headers =  array("api-key: {$_ENV['API_KEY']}");
        $url = "{$this->baseURL}/integrations/payments/key";
        $response = Curl::get($url, $headers);
        $data = $response['data'];
        $key = $data && array_key_exists('key', $data) ? $data['key'] : '';
        return $key;
    }

    public function checkout(array $card)
    {
        try {
            $randomPassphrase = base64_encode(openssl_random_pseudo_bytes(16));
            $encryptedCard = Crypt::encrypt(json_encode($card), $randomPassphrase);

            $headers =  array("api-key: {$_ENV['API_KEY']}", 'Content-Type: application/json');
            $url = "{$this->baseURL}/integrations/payments/checkout";

            $encryptedKey = Crypt::encryptWithPublicKey($randomPassphrase, $this->getPublicKey());
            $json = json_encode(array(
                'storeId' => 25, // TODO: remove this raw ID.
                'data' => $encryptedCard,
                'key' => $encryptedKey
            ));

            $response = Curl::post($url, $headers, $json);
            return $response;
        } catch (\Throwable $th) {
            return Json::formatJSONResponse(false, 400, $th->getMessage(), $th->getCode());
        }
    }
}
