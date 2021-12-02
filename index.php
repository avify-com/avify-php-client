<?php

use App\Avify;

require_once realpath("vendor/autoload.php");

$card = new Avify('test', 'test', 'test', 'test', 'test', 'test');
var_dump($card);