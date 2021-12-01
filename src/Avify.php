<?php

require '../vendor/autoload.php';

Class Avify {

    public $prod_base_url = 'https://api.avify.co';
    public $sandbox_base_url = 'https://sandboxapi.avify.co';
    public $base_url;
    public $mode;
    public $public_key;

    public function __construct($prod_base_url, $sadbox_baseUrl, $base_url, $mode, $public_key){
        $this->prod_base_url = $prod_base_url;
        $this->sadbox_base_url = $sadbox_base_url;
        $this->base_url = $base_url;
        $this->mode = $mode;
        $this->public_key = $public_key;
    }

    function set_prod_base_url($prod_base_url){
        $this->prod_base_url = $prod_base_url;
    }
    function get_prod_base_url(){
        return $this->prod_base_url;
    }

    function set_sandbox_base_url($sandbox_base_url){
        $this->sandbox_base_url = $sandbox_base_url;
    }
    function get_sadbox_baseUrl(){
        return $this->sadbox_baseUrl;
    }

    function set_base_url($base_url){
        $this->base_url = $base_url;
    }
    function get_base_url(){
        return $this->base_url;
    }

    function set_mode($mode){
        $this->mode = $mode;
    }
    function get_mode(){
        return $this->mode;
    }
    
    function set_public_key($public_key){
        $this->public_key = $public_key;
    }
    function get_public_key(){
        return $this->public_key;
    }
}

?>
