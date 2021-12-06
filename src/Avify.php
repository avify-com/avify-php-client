<?php
declare(strict_types = 1);
namespace App;

Class Avify {

    public $prod_base_url;
    public $sandbox_base_url;
    public $base_url;
    public $mode;
    public $public_key;

    public function __construct(string $prod_base_url , string $sandbox_base_url, string $base_url, string $mode, string $public_key){
        $this->prod_base_url = $prod_base_url;
        $this->sandbox_base_url = $sandbox_base_url;
        $this->base_url = $base_url;
        $this->mode = $mode;
        $this->public_key = $public_key;
    }

    function set_prod_base_url(string $prod_base_url){
        $this->prod_base_url = $prod_base_url;
    }
    function get_prod_base_url(){
        return $this->prod_base_url;
    }

    function set_sandbox_base_url(string $sandbox_base_url){
        $this->sandbox_base_url = $sandbox_base_url;
    }
    function get_sandbox_base_url(){
        return $this->sandbox_base_url;
    }

    function set_base_url(string $base_url){
        $this->base_url = $base_url;
    }
    function get_base_url(){
        return $this->base_url;
    }

    function set_mode(string $mode){
        $this->mode = $mode;
    }
    function get_mode(){
        return $this->mode;
    }
    
    function set_public_key(string $public_key){
        $this->public_key = $public_key;
    }
    function get_public_key(){
        return $this->public_key;
    }


    public function get_key(string $mode, string $version){


        $curl = curl_init();
        $headers = [
            'avify-auth: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEyLCJhcGlJZCI6MTgsInR5cGUiOiJBUEkiLCJpYXQiOjE2Mzg0Njc0OTB9.L2-G5UyDgLY_5hKSwnWV6kyJBzbnz5D-hVAkNi1U4Hv0e102sYZYnu4Q755K9y1dlBr1SRyHOjOUdEido_dxUEMu_oFYl57i2YaHN68RtZqV5KtbbjAtl3hnityD6PeejP_j4sQP6yKd6ObiEYZSSY_-e7hlWPZcOtK1Q47uwAPUvow6KDceB3B8bsoiudCiaD956-6TVenafry2oMzkkoCDmpGjQg2Hh0g76l86FAsn2DB0YhVd-XY0YhoffsnWVN7OuCfaJbBD1FBvI7tgTT6GmIjkqqKvW6LFsjvchRkWRy6B5p7yKmrCdu6ETrLePU4aePJ8_1d4qZtnZPWfY1csqA6FgYk2G5bIFUcnbQeomaJXQFWOe617ISxIvvo_Yq5T2tEqUpyq5mTw1KBeXu6Cuh7xjugmqz9Hgeb-_KofKHXlJd_2GNs929pqlQLAOHUEScNJhNCvzy0qzomQJIb9-2mzwCF99VlpAriCRjtCIvW5FdHPv0918we-ERh6KhrFMbuEjr9IyHV6G90nIMJJwa5O3dFhRjugN0EykRjNyxZLz_6kg_uEFoEP0SsHu5JQJCoUJlYXDjrmluN1xfPeiQdfUTp-2jCaJmzEMM95eq9zS5tD296pybemYbmH8nJyE-nO-YcZzWyYmVyWO-nhFZwYKGK-wyyDj0-JJQc
            '
        ];
        $base = ($mode == 'sandbox') ? 'https://sandboxapi.avify.co' : 'https://api.avify.co' ;
        echo $base;
        curl_setopt($curl, CURLOPT_URL, "{$base}/api/{$version}/gateway/key");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $key = curl_exec($curl);
        if (curl_errno($curl)) {
            print "Error: " . curl_error($curl);
            exit();
        }
        return $key;
        curl_close($curl);
    }

    public function encrypt(string $key, string $data){
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted .'::'.$iv);
    }

    
}

?>
