<?php

declare(strict_types=1);

use App\AES;
use App\Avify;
use App\Card;
use App\Checkout;

require_once realpath("vendor/autoload.php");

$test1 = new Avify('111', 'sd', 'test', 'test', 'test');
$test2 = new AES('111', 'sd', 'test', 'test', 'test');

$key = $test2->get_key('sandbox', 'v1');

$arr1 = ['cardHolder' => 'Alexis Valenciano', 'cvc' => '248', 'expMonth' => '06', 'expYear' => '2023', 'carcNumber' => '2424242424242424'];
$arr2 = [
    'cardHolder' => 'Alexis Valenciano',
    'cardNumber' => '2424242424242424',
    'cvc' => '248',
    'expMonth' => '06',
    'expYear' => '2023'
];

$arr3 = [
    "amount" => 500.23, //required
    "currency" => "USD", //required
    "description" => "Pago de comercio", //required
    "storeId" => "123456", //required,
    "orderReference" => "abc123",
    "card" => [
        'cardHolder' => 'Alexis Valenciano',
        'cardNumber' => '2424242424242424',
        'cvc' => '248',
        'expMonth' => '06',
        'expYear' => '2023'
    ],
    "customerId" => "123456789", //optional=> if customerId is provided then customer object will be ignored
    "customer" => [
        "lastName" => "Quiroga", //required
        "firstName" => "Eduardo", //required
        "email" => "equiroga@bananacode.co", //required
        "company" => "Bananacode", //optional
        "shippingAddress" => [
            "addressLine1" => "Avenida 10, Calle 15",
            "addressLine2" => "Los Pardos",
            "country" => "Costa Rica", //required
            "state" => "Heredia", //required
            "district" => "Concepción", //optional
            "city" => "San Rafael", //required
            "postCode" => "40501", //required
            "geoLat" => 9.087, //optional
            "geoLon" => 1.246, //optional
            "label" => "Work"
        ],
        "billingAddress" => [
            "addressLine1" => "Avenida 10, Calle 15",
            "addressLine2" => "Los Pardos",
            "country" => "Costa Rica", //required
            "state" => "Heredia", //required
            "district" => "Concepción", //optional
            "city" => "San Rafael", //required
            "postCode" => "40501", //required
            "geoLat" => 9.087, //optional
            "geoLon" => 1.246, //optional
            "label" => "Work"
        ]
    ],
    "meta" => ["orderId" => "abcd1234"], //optional 
];



$card1 = new Checkout($arr1);
$card2 = new Card();
$card2->setCardHolder($arr2['cardHolder']);
$card2->setCardNumber($arr2['cardNumber']);
$card2->setCVC($arr2['cvc']);
$card2->setExpMonth($arr2['expMonth']);
$card2->setExpYear($arr2['expYear']);


// $aes = $test2->encrypt( json_encode($arr), $key);
// $aesdec = $test2->decrypt( $aes, $key);

// $chk1 = $test1->checkout($card1);
$chk2 = $test2->checkout($card2);

// echo 'AES1 => ' . $aes . '\n';
// echo 'AES1 DEC => ' . $aesdec . '\n';
// echo 'CHK1 Avify => ' . $chk1 . '\n';
echo 'CHK1 AES => ' . $chk2 . '\n';
