<?php
declare(strict_types = 1);
use App\Avify;
use App\Checkout;


require_once realpath("vendor/autoload.php");

$test = new Avify('111', 'sd', 'test', 'test', 'test');
$key = $test->get_key('sandbox', 'v1');
$arr = array('cardHolder' => 'Alexis Valenciano', 'cvc' => '248', 'expMonth' =>'06', 'expYear' =>'2023', 'carcNumber' => '2424242424242424');
$card = new Checkout($arr);
echo $test->encrypt($key, json_encode($arr)) ;
//echo $test->encrypt1(json_encode($arr)) ;
echo '<br>';
//echo $test->checkout(($card));
//print_r($test->guzzle_checkout($card));
echo $test->encrypt5();


