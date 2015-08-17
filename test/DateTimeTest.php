<?php

namespace alroniks\dtms\Test;

use alroniks\dtms\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testCreateFromFormat()
    {
        $dt = DateTime::createFromFormat(DateTime::ISO8601, '2015-08-08T10:10:10.123456Z', new \DateTimeZone('UTC'));

        $this->assertEquals('123456', $dt->getMicroseconds());
    }
}
