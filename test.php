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
$dt = DateTime::createFromFormat('U.u', '1439217570.654321', null);
print_r($dt);

$i = new DateInterval('PT20.654321S');
echo $i->format('PT%sS'), "\n";

$dt2 = clone $dt;
$dt2->add($i);
//print_r($dt2->format(DateTime::ISO8601)); echo "\n";

$dt3 = clone $dt;
$dt3->sub($i);
//print_r($dt3->format(DateTime::ISO8601)); echo "\n";

$dt4 = clone $dt;
$dt4->sub(new DateInterval('PT20.999999S'));
//print_r($dt4->format(DateTime::ISO8601)); echo "\n";

// diff
$dt2->diff($dt3);

