<?php
declare(strict_types = 1);
use App\Avify;

require_once realpath("vendor/autoload.php");

$test = new Avify('111', 'sd', 'test', 'test', 'test');

$key = $test->get_key('sandbox', 'v1');
$arr = json_encode(array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5));
echo $test->encrypt($key, $arr) ;
