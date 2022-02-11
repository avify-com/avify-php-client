<?php

declare(strict_types=1);

namespace App;

use App\Utils\Curl;
use App\Utils\Crypt;
use App\Utils\Json;

class Avify {
    private $api_key;
    private $base_url;
    private $public_key;
    private $prod_base_url = 'https://api.avify.com';
    private $sandbox_base_url = 'https://sandboxapi.avify.co';
    private $locale = 'en'; // English by default

    /**
     * Constructor.
     * 
     * @param string $mode    API mode 'sandbox' | 'production'.
     * @param string $version API version 'v1'.
     * @param string $api_key Your API key.
     */
    public function __construct(string $mode, string $version, string $api_key) {
        $this->base_url = $this->get_base_url($mode, $version);
        $this->api_key = $api_key;
        $this->public_key = $this->get_payments_public_key();
    }

    /**
     * Returns the base url according to the API mode and version.
     * 
     * @param string $mode    API mode 'sandbox' | 'production'.
     * @param string $version API version 'v1'.
     * 
     * @return string Base url or an empty string if the given mode and/or version are incorrect.
     */
    private function get_base_url(string $mode, string $version) {
        $accepted_api_modes = array(
            'production' => $this->prod_base_url,
            'sandbox' => $this->sandbox_base_url,
        );
        $accepted_api_versions = array('v1');
        $parsed_mode = strtolower(trim($mode));
        $parsed_version = strtolower(trim($version));

        if (array_key_exists($parsed_mode, $accepted_api_modes) && in_array($parsed_version, $accepted_api_versions)) {
            return $accepted_api_modes[$parsed_mode] . '/api/' . $parsed_version;
        }
        return '';
    }

    /**
     * Sets the language for returned messages.
     * 
     * @param string $locale The desired locale (i.e: en_US).
     */
    public function set_locale(string $locale) {
        $available_locales = array('en', 'es');
        $locale_primary_language = locale_get_primary_language($locale);

        if (isset($locale_primary_language) && in_array($locale_primary_language, $available_locales)) {
            $this->locale = $locale_primary_language;
        }
    }

    /**
     * Gets a custom message from an error code.
     * 
     * @param string $error_code        The code of the error you want to show.
     * @param string $developer_message (Optional) If you want to show an technical error for debugging purposes.
     * 
     * @return array JSON response with the custom error.
     */
    private function get_error_message_from_code(string $error_code, string $developer_message = '') {
        $error_messages = array(
            'AP-019' => array(
                'es' => 'La versión o el modo de la API es incorrecto',
                'en' => 'The API version or mode is incorrect'
            ),
            'AP-020' => array(
                'es' => 'La cantidad a pagar debe tener dos decimales',
                'en' => 'The amount to pay must have two decimals'
            ),
            'G-000' => array(
                'es' => 'Parece que algo salió mal',
                'en' => 'Something went wrong'
            )
        );
        $default_error_code = 'G-000';
        $error_message = $error_messages[$default_error_code][$this->locale];

        if (array_key_exists($error_code, $error_messages) && $error_code !== $default_error_code) {
            $error_message = $error_messages[$error_code][$this->locale];
        }
        return Json::format_json_response(false, 400, $error_message, $error_code, $developer_message);
    }

    /**
     * Returns the payments public key.
     * 
     * @return string Public key or an empty string if the key was not found.
     */
    public function get_payments_public_key() {
        $headers =  array("api-key: {$this->api_key}");
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
            if ($this->base_url === '') {
                return $this->get_error_message_from_code('AP-019');
            }
            if (array_key_exists('amount', $payment_data) && !is_float($payment_data['amount'])) {
                return $this->get_error_message_from_code('AP-020');
            }
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
            return $this->get_error_message_from_code('G-000', $th->getMessage());
        }
    }
}
