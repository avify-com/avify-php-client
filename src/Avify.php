<?php

declare(strict_types=1);

namespace App;

use App\Utils\Curl;
use App\Utils\Crypt;
use App\Utils\Json;

class Avify {
    private $api_key;
    private $base_url;
    private $mode; // TODO: add accepted modes 'sandbox' | 'production' and validate the given mode.
    private $public_key;
    private $prod_base_url = 'https://api.avify.co';
    private $sandbox_base_url = 'https://sandboxapi.avify.co';

    /**
     * Constructor.
     * 
     * @param string $mode    API mode 'sandbox' | 'production'.
     * @param string $version API version 'v1'.
     * @param string $api_key Your API key.
     */
    public function __construct(string $mode, string $version, string $api_key) {
        $this->base_url =
            ($mode === 'sandbox'
                ? $this->sandbox_base_url
                : $this->prod_base_url
            ) . '/api/' . $version;
        $this->api_key = $api_key;
        $this->public_key = $this->get_payments_public_key();
    }

    /**
     * Returns the payments public key.
     * 
     * @return string Public key or an empty string if the key was not found.
     */
    public function get_payments_public_key() {
        $headers =  ["api-key: {$this->api_key}"];
        $url = "{$this->base_url}/integrations/payments/key";
        $response = Curl::get($url, $headers);
        $data = $response['data'];
        $key = $data && array_key_exists('key', $data) ? $data['key'] : '';
        return $key;
    }

    /**
     * Process the payment for an order and provide a result.
     * 
     * @param array  $payment_data Payment values.
     * @param int    $store_id     ID of the store where the order was created.
     * 
     * @return array JSON response with httpCode, success (true/false) and data or error.
     */
    public function process_payment(array $payment_data, int $store_id) {
        try {
            $random_passphrase = base64_encode(openssl_random_pseudo_bytes(16));
            $encrypted_payment_data = Crypt::encrypt_aes_256_gcm(
                json_encode($payment_data),
                $random_passphrase
            );
            $encrypted_key = Crypt::encrypt_aes_256_ctr(
                $random_passphrase,
                $this->public_key
            );

            $headers =  ["api-key: {$this->api_key}", 'Content-Type: application/json'];
            $url = "{$this->base_url}/integrations/payments/checkout";

            $json = json_encode(array(
                'storeId' => $store_id,
                'data' => $encrypted_payment_data,
                'key' => $encrypted_key
            ));

            $response = Curl::post($url, $headers, $json);
            return $response;
        } catch (\Throwable $th) {
            return Json::format_json_response(false, 500, $th->getMessage(), $th->getCode());
        }
    }
}
