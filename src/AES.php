<?php

declare(strict_types=1);

namespace App;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 1), '.env');
$dotenv->load();

class AES
{

    public $prod_base_url;
    public $sandbox_base_url;
    public $base_url;
    public $mode;
    public $public_key;

    public function __construct(string $prod_base_url, string $sandbox_base_url, string $base_url, string $mode, string $public_key)
    {
        $this->prod_base_url = $prod_base_url;
        $this->sandbox_base_url = $sandbox_base_url;
        $this->base_url = $base_url;
        $this->mode = $mode;
        $this->public_key = $public_key;
    }

    function set_prod_base_url(string $prod_base_url)
    {
        $this->prod_base_url = $prod_base_url;
    }
    function get_prod_base_url()
    {
        return $this->prod_base_url;
    }

    function set_sandbox_base_url(string $sandbox_base_url)
    {
        $this->sandbox_base_url = $sandbox_base_url;
    }
    function get_sandbox_base_url()
    {
        return $this->sandbox_base_url;
    }

    function set_base_url(string $base_url)
    {
        $this->base_url = $base_url;
    }
    function get_base_url()
    {
        return $this->base_url;
    }

    function set_mode(string $mode)
    {
        $this->mode = $mode;
    }
    function get_mode()
    {
        return $this->mode;
    }

    function set_public_key(string $public_key)
    {
        $this->public_key = $public_key;
    }
    function get_public_key()
    {
        return $this->public_key;
    }


    public function get_key(string $mode, string $version)
    {
        $curl = curl_init();
        $headers =  array("api-key: {$_ENV['API_KEY']}");
        $base = ($mode == 'sandbox') ? 'https://sandboxapi.avify.co' : 'https://api.avify.co';

        curl_setopt($curl, CURLOPT_URL, "{$base}/api/{$version}/integrations/payments/key");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            print "Error: " . curl_error($curl);
            exit();
        }

        $decodedResponse = json_decode($response, true);
        return $decodedResponse["key"];
        curl_close($curl);
    }
    /**
     * Encrypts data with the supplied passphrase, using AES-256-CTR.
     * 
     * @param string $plaintext the plaintext data.
     * @param string $passphrase a passphrase/password.
     * @return string|false encrypted data: iv + ciphertext or `false` on error.
     */
    function encryptKey($plaintext)
    {
        $key = $this->get_key('sandbox', 'v1');
        openssl_public_encrypt($plaintext, $encSymKey, $key);
        return base64_encode($encSymKey);
    }

    /**
     * Encrypts data with the supplied passphrase, using AES-256-GCM and PBKDF2-SHA256.
     * 
     * @param string $plaintext the plaintext data.
     * @param string $passphrase a passphrase/password.
     * @return string|false encrypted data: salt + nonce + ciphertext + tag or `false` on error.
     */
    function encrypt(string $plaintext, string $passphrase)
    {
        $salt = openssl_random_pseudo_bytes(16);
        $nonce = openssl_random_pseudo_bytes(12);
        $key = hash_pbkdf2("sha256", $passphrase, $salt, 40000, 32, true);
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, 1, $nonce, $tag);

        return base64_encode($salt . $nonce . $ciphertext . $tag);
    }

    /**
     * Decrypts data with the supplied passphrase, using AES-256-GCM and PBKDF2-SHA256.
     * 
     * @param string $ciphertext encrypted data.
     * @param string $passphrase a passphrase/password.
     * @return string|false plaintext data or `false` on error.
     */
    function decrypt(string $ciphertext, string $passphrase)
    {
        $input = base64_decode($ciphertext);
        $salt = substr($input, 0, 16);
        $nonce = substr($input, 16, 12);
        $ciphertext = substr($input, 28, -16);
        $tag = substr($input, -16);
        $key = hash_pbkdf2("sha256", $passphrase, $salt, 40000, 32, true);

        return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, 1, $nonce, $tag);
    }

    public function checkout($card)
    {
        // This passphrase is used for testing purposes only.
        // The key will be part of the request.
        // TODO: remove this randomPassphrase.
        $randomPassphrase = base64_encode(openssl_random_pseudo_bytes(16));
        $encryptedCard = $this->encrypt(json_encode($card), $randomPassphrase);

        $curl = curl_init();
        $headers =  array("api-key: {$_ENV['API_KEY']}", 'Content-Type: application/json');

        $encryptedKey = $this->encryptKey($randomPassphrase);
        $json = json_encode(array(
            'storeId' => 25, // TODO: remove this raw ID.
            'data' => $encryptedCard,
            'key' => $encryptedKey
        ));

        // echo '\JSON: ' . $json . '\n';

        curl_setopt($curl, CURLOPT_URL, "https://sandboxapi.avify.co/api/v1/integrations/payments/checkout");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        # Return response instead of printing.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            print "Checkout Error: " . curl_error($curl);
            exit();
        }
        return $response;
        curl_close($curl);
    }
}
