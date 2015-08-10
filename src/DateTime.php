<?php

namespace alroniks\dtms;

class DateTime extends \DateTime
{
    const ISO8601U = 'Y-m-d\TH:i:s.uO';

    public function __construct($time = 'now', \DateTimeZone $timezone = null)
    {

    }

    public function setTime($hour, $minute, $second = 0, $microsecond = 0)
    {

    }

    // add
    // sub
    // modify

    // setTimestamp // with ms
    // getTimestamp // with ms

    // diff

    // createFromFormat - with ms
    // __set_state - with ms, not sure
}
