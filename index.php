<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Avify;

$test = new Avify('sandbox');

$arr = [
    "amount" => 500, //required
    "currency" => "USD", //required
    "description" => "Pago de comercio", //required
    "orderReference" => "abc123",
    "card" => [ //required
        "cardHolder" => "Eduardo Quiroga", //required
        "cardNumber" => "4242424242424242", //required
        "cvc" => 123, //required
        "expMonth" => 12, //required
        "expYear" => 2028 //required
    ],
    // "customerId"=>"123456789",//optional=> if customerId is provided then customer object will be ignored
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
    "meta" => [ //optional 
        "orderId" => "abcd1234"
    ]
];

$chk = $test->checkout($arr);

echo 'CHK1 AES => \n';
var_dump($chk);
