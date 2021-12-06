<?php
declare(strict_types = 1);
use App\Avify;

require_once realpath("vendor/autoload.php");

$card = new Avify('111', 'sd', 'test', 'test', 'test');

echo $card->get_key('sandbox', 'v1');
