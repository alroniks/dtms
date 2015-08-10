<?php

include "vendor/autoload.php";

use alroniks\dtms\DateInterval;
use alroniks\dtms\DateTime;

//$i = new DateInterval('PT20.005S');
//
//print_r($i);
//
//echo "\n";
//
//print_r($i->format('%d days, %s seconds, %u microseconds'));
//
//echo "\n";
//
//print_r($i->format('%D days, %S seconds, %U microseconds'));
//
//echo "\n";
//
//print_r(DateInterval::createFromDateString('2 days, 20   seconds, 5 microseconds'));
//print_r(DateInterval::createFromDateString('2 days, 20  seconds, 5 microseconds ago'));

//$dt = new DateTime();

//$dt = new DateTime();
$dt = DateTime::createFromFormat('U.u', '1439217570.0501');

$i = new DateInterval('PT20.0201S');

print_r($dt);
echo $i->format('PT%HH%II%SS'), "\n";

$dt->add($i);

print_r($dt);

