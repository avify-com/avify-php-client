<?php
declare(strict_types = 1);
namespace App;


use App\Checkout;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;


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
            'api-key: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEyLCJhcGlJZCI6MTgsInR5cGUiOiJBUEkiLCJpYXQiOjE2Mzg0Njc0OTB9.L2-G5UyDgLY_5hKSwnWV6kyJBzbnz5D-hVAkNi1U4Hv0e102sYZYnu4Q755K9y1dlBr1SRyHOjOUdEido_dxUEMu_oFYl57i2YaHN68RtZqV5KtbbjAtl3hnityD6PeejP_j4sQP6yKd6ObiEYZSSY_-e7hlWPZcOtK1Q47uwAPUvow6KDceB3B8bsoiudCiaD956-6TVenafry2oMzkkoCDmpGjQg2Hh0g76l86FAsn2DB0YhVd-XY0YhoffsnWVN7OuCfaJbBD1FBvI7tgTT6GmIjkqqKvW6LFsjvchRkWRy6B5p7yKmrCdu6ETrLePU4aePJ8_1d4qZtnZPWfY1csqA6FgYk2G5bIFUcnbQeomaJXQFWOe617ISxIvvo_Yq5T2tEqUpyq5mTw1KBeXu6Cuh7xjugmqz9Hgeb-_KofKHXlJd_2GNs929pqlQLAOHUEScNJhNCvzy0qzomQJIb9-2mzwCF99VlpAriCRjtCIvW5FdHPv0918we-ERh6KhrFMbuEjr9IyHV6G90nIMJJwa5O3dFhRjugN0EykRjNyxZLz_6kg_uEFoEP0SsHu5JQJCoUJlYXDjrmluN1xfPeiQdfUTp-2jCaJmzEMM95eq9zS5tD296pybemYbmH8nJyE-nO-YcZzWyYmVyWO-nhFZwYKGK-wyyDj0-JJQc'
        ];
        $base = ($mode == 'sandbox') ? 'https://sandboxapi.avify.co' : 'https://api.avify.co' ;
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
    public function get_key1(string $mode, string $version){

        $curl = curl_init();
        $headers = [
            'api-key: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEyLCJhcGlJZCI6MTgsInR5cGUiOiJBUEkiLCJpYXQiOjE2Mzg0Njc0OTB9.L2-G5UyDgLY_5hKSwnWV6kyJBzbnz5D-hVAkNi1U4Hv0e102sYZYnu4Q755K9y1dlBr1SRyHOjOUdEido_dxUEMu_oFYl57i2YaHN68RtZqV5KtbbjAtl3hnityD6PeejP_j4sQP6yKd6ObiEYZSSY_-e7hlWPZcOtK1Q47uwAPUvow6KDceB3B8bsoiudCiaD956-6TVenafry2oMzkkoCDmpGjQg2Hh0g76l86FAsn2DB0YhVd-XY0YhoffsnWVN7OuCfaJbBD1FBvI7tgTT6GmIjkqqKvW6LFsjvchRkWRy6B5p7yKmrCdu6ETrLePU4aePJ8_1d4qZtnZPWfY1csqA6FgYk2G5bIFUcnbQeomaJXQFWOe617ISxIvvo_Yq5T2tEqUpyq5mTw1KBeXu6Cuh7xjugmqz9Hgeb-_KofKHXlJd_2GNs929pqlQLAOHUEScNJhNCvzy0qzomQJIb9-2mzwCF99VlpAriCRjtCIvW5FdHPv0918we-ERh6KhrFMbuEjr9IyHV6G90nIMJJwa5O3dFhRjugN0EykRjNyxZLz_6kg_uEFoEP0SsHu5JQJCoUJlYXDjrmluN1xfPeiQdfUTp-2jCaJmzEMM95eq9zS5tD296pybemYbmH8nJyE-nO-YcZzWyYmVyWO-nhFZwYKGK-wyyDj0-JJQc'
        ];
        $base = ($mode == 'sandbox') ? 'https://sandboxapi.avify.co' : 'https://api.avify.co' ;
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
    public function encrypt4(){
        $key = $this->get_key('sandbox', 'v1');
        $key1 = str_replace(' ', '+', $key);
        $encryption_key = base64_decode($key);
        openssl_public_encrypt('eres lindo', $encSymKey, $encryption_key);
        echo base64_encode((string)$encSymKey);
    }
    public function encrypt5(){
        $key1 = file_get_contents('https://sandboxapi.avify.co/api/v1/gateway/key');
        $key = $this->get_key('sandbox', 'v1');
        $encryption_key = base64_decode($key);
        openssl_public_encrypt('eres lindo', $encSymKey, $key);
        echo base64_encode((string)$encSymKey);
    }
    public function encrypt(string $key, string $data){
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-ctr'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted);
    }
    function encrypt1(string $plaintext) {
        $salt = openssl_random_pseudo_bytes(16);
        $nonce = openssl_random_pseudo_bytes(12);
        $key = $this->get_key('sandbox', 'v1');
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, 1, $nonce, $tag);
    
        return base64_encode($salt.$nonce.$ciphertext.$tag);
    }
    public function encrypt2(string $card, string $publicKey){
        $key = \random_bytes(32);
        $C = '';

        // This defaults to PKCS1v1.5:
        \openssl_public_encrypt($key, $C, $publicKey);

        $iv = \random_bytes(16);
        $cipher = \openssl_encrypt(
            $card,
            'aes-256-ctr',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $mac = \hash_hmac('sha256', $iv . $cipher, $key, true);
        $D = $mac . $iv . $cipher;
        return \bin2hex($D);
    }
    public function checkout(Checkout $checkout){
        $key = $this->get_key('sandbox', 'v1');
       // $encrypted_card = array('card' => $this->encrypt2(json_encode($checkout), $key) );
        $encrypted_card = json_encode(array('card' =>'lirTMfehfB2869cBn6jgE7gqKCIDGZiSO0m75eR01Ncl4Az5jUARwmzgwLw79Y0/8eUEBm0X7odw8RAE6zGbvTUjZWnk1vo8V6fUsRQlShfJSgqXciHXYM9UIM8+tGcOlj1MaBMh/Q4EsHjjDv6+0oPn7u0wg7roIWPsDWaX2ugqp++c8r0l+Zhr8n4qXvbnhe7RqZu6669Q8FEVpmzfg5Zu1xfSwk3zWTPixinWezGm5XWckUu9ThmDRuQv6p1v/F1O7bXVtOfmfdhRqLaVDJG2XIJ1Qina4St7lNsnSs9YT5SxXV+T3UDMzKCg6zO9b2YX3tSi53RBwoB1zfWed3qxX/R8LdDDqF1Id2vAKByFBSz2oPMc/6PkwAvJzw0mhbbfR3kCQseS6KkJ1I684QO2VIIGyNsGt7F8u59kDsK7blbhfjYvXjjoxNc1t5Re+MBpqklBHTl6wib2rRryNN6Iw3bVlbBWpWOJIUz9z30IlA5JHYVMxTb1lVfPvwg0FSzrB0aF6Dst/pAjdxs2Dya0H73hwfvcFvmejgYjt/kVPM/Pn8cB5lZfw6Fn2mbBNJTkPu2yrW0RnR6XbVTKSgGoeLpHl/Um18zP01MS4VnF89UXg2zoy3YgMISh21m2zSjMn/6AoWhSk2W4EahOhrrSyh44hKXEgxUVk6iI8bQ='));

        print_r('Checkout'.$encrypted_card);
        $curl = curl_init();
        $headers = [
            'api-key: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEyLCJhcGlJZCI6MTgsInR5cGUiOiJBUEkiLCJpYXQiOjE2Mzg0Njc0OTB9.L2-G5UyDgLY_5hKSwnWV6kyJBzbnz5D-hVAkNi1U4Hv0e102sYZYnu4Q755K9y1dlBr1SRyHOjOUdEido_dxUEMu_oFYl57i2YaHN68RtZqV5KtbbjAtl3hnityD6PeejP_j4sQP6yKd6ObiEYZSSY_-e7hlWPZcOtK1Q47uwAPUvow6KDceB3B8bsoiudCiaD956-6TVenafry2oMzkkoCDmpGjQg2Hh0g76l86FAsn2DB0YhVd-XY0YhoffsnWVN7OuCfaJbBD1FBvI7tgTT6GmIjkqqKvW6LFsjvchRkWRy6B5p7yKmrCdu6ETrLePU4aePJ8_1d4qZtnZPWfY1csqA6FgYk2G5bIFUcnbQeomaJXQFWOe617ISxIvvo_Yq5T2tEqUpyq5mTw1KBeXu6Cuh7xjugmqz9Hgeb-_KofKHXlJd_2GNs929pqlQLAOHUEScNJhNCvzy0qzomQJIb9-2mzwCF99VlpAriCRjtCIvW5FdHPv0918we-ERh6KhrFMbuEjr9IyHV6G90nIMJJwa5O3dFhRjugN0EykRjNyxZLz_6kg_uEFoEP0SsHu5JQJCoUJlYXDjrmluN1xfPeiQdfUTp-2jCaJmzEMM95eq9zS5tD296pybemYbmH8nJyE-nO-YcZzWyYmVyWO-nhFZwYKGK-wyyDj0-JJQc',
            'content-type' => 'application/json'
        ];
        curl_setopt($curl, CURLOPT_URL, "https://sandboxapi.avify.co/api/v1/gateway/checkout");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $encrypted_card);
        

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            print "Checkout Error: " . curl_error($curl);
            exit();
        }
        return $response;
        curl_close($curl);
    }
    public function guzzle_checkout(Checkout $checkout){
        $key = $this->get_key('sandbox', 'v1');
        $encrypted_card = $this->encrypt1(json_encode($checkout), $key);
        $headers = [
            'api-key' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEyLCJhcGlJZCI6MTgsInR5cGUiOiJBUEkiLCJpYXQiOjE2Mzg0Njc0OTB9.L2-G5UyDgLY_5hKSwnWV6kyJBzbnz5D-hVAkNi1U4Hv0e102sYZYnu4Q755K9y1dlBr1SRyHOjOUdEido_dxUEMu_oFYl57i2YaHN68RtZqV5KtbbjAtl3hnityD6PeejP_j4sQP6yKd6ObiEYZSSY_-e7hlWPZcOtK1Q47uwAPUvow6KDceB3B8bsoiudCiaD956-6TVenafry2oMzkkoCDmpGjQg2Hh0g76l86FAsn2DB0YhVd-XY0YhoffsnWVN7OuCfaJbBD1FBvI7tgTT6GmIjkqqKvW6LFsjvchRkWRy6B5p7yKmrCdu6ETrLePU4aePJ8_1d4qZtnZPWfY1csqA6FgYk2G5bIFUcnbQeomaJXQFWOe617ISxIvvo_Yq5T2tEqUpyq5mTw1KBeXu6Cuh7xjugmqz9Hgeb-_KofKHXlJd_2GNs929pqlQLAOHUEScNJhNCvzy0qzomQJIb9-2mzwCF99VlpAriCRjtCIvW5FdHPv0918we-ERh6KhrFMbuEjr9IyHV6G90nIMJJwa5O3dFhRjugN0EykRjNyxZLz_6kg_uEFoEP0SsHu5JQJCoUJlYXDjrmluN1xfPeiQdfUTp-2jCaJmzEMM95eq9zS5tD296pybemYbmH8nJyE-nO-YcZzWyYmVyWO-nhFZwYKGK-wyyDj0-JJQc',
            'content-type' => 'application/json'
        ];
        $client = new Client([
            'headers' => $headers
        ]);

        $response = $client->request('POST', 'https://sandboxapi.avify.co/api/v1/gateway/checkout', [
            'json' => [
                'card' => $encrypted_card,
            ]
        ]);
        $body = $response->getBody();
        $arr_body = json_decode((string)$body);
        return $arr_body;
        
    }
    
}

?>
