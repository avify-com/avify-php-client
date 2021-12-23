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
    function encrypt($plaintext)
    {
        $key = $this->get_key('sandbox', 'v1');
        openssl_public_encrypt($plaintext, $encSymKey, $key);
        return base64_encode($encSymKey);
    }

    public function checkout(Card $card)
    {
        $encryptedCard = $this->encrypt(json_encode($card));

        $curl = curl_init();        
        $headers =  array("api-key: {$_ENV['API_KEY']}", 'Content-Type: application/json');


        // $aes = $this->encrypt( json_encode($card));
        // $aesdec = $this->decrypt( $aes, $key);

        // $aes = $this->encrypt("Hello World!", $key);
        // $aesdec = $this->decrypt( $aes, $key);

        // echo 'Card: \n';
        // var_dump($card);
        // echo 'AES: ' . $encryptedCard . '\n';
        // echo 'AES DEC: ' . $aesdec . '\n';

        
        $json = json_encode(array('card' => $encryptedCard));
        
        echo '\JSON: ' . $json . '\n';
        

        curl_setopt($curl, CURLOPT_URL, "https://sandboxapi.avify.co/api/v1/integrations/payments/checkout");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        # Return response instead of printing.
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            print "Checkout Error: " . curl_error($curl);
            exit();
        }
        return $response;
        curl_close($curl);

        // $postValue = json_encode(array('card' => $encryptedCard));
        // return $postValue;
    }
}
