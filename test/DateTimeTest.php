<?php

namespace alroniks\dtms\Test;

use alroniks\dtms\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        date_default_timezone_set('UTC');
    }

    public function tearDown()
    {
        // do nothing
    }

    /**
     * @covers alroniks\dtms\DateTime::getMicroseconds
     */
    public function testSetMicroseconds()
    {
        $dt = new DateTime();
        $dt->setMicroseconds(123456);
        $this->assertSame(123456, $dt->microseconds);
        $dt->setMicroseconds('123456');
        $this->assertSame(123456, $dt->microseconds);

        $dt->setMicroseconds(654);
        $this->assertSame(654, $dt->microseconds);

        $dt->setMicroseconds('987000');
        $this->assertSame(987000, $dt->microseconds);

        $dt->setMicroseconds('000123');
        $this->assertSame(123, $dt->microseconds);
    }

    /**
     * @covers alroniks\dtms\DateTime::getMicroseconds
     */
    public function testGetMicroseconds()
    {
        $dt = new DateTime();
        $dt->microseconds = 123456;
        $this->assertSame(123456, $dt->getMicroseconds());
        $this->assertSame(0.123456, $dt->getMicroseconds(true));

        $dt->microseconds = 456;
        $this->assertSame(456, $dt->getMicroseconds());
        $this->assertSame(0.000456, $dt->getMicroseconds(true));
    }

    /**
     * @covers alroniks\dtms\DateTime::createFromFormat
     */
    public function testCreateFromFormat()
    {
        $dt1 = new DateTime('2015-08-08 10:10:10.123456');
        $dt2 = DateTime::createFromFormat(DateTime::ISO8601, '2015-08-08T10:10:10.123456Z');

        $this->assertEquals($dt1, $dt2);
    }

    /**
     * @covers alroniks\dtms\DateTime::__construct
     */
    public function testConstruct()
    {
        $dt = new DateTime();
        $this->assertInstanceOf('alroniks\\dtms\\DateTime', $dt);
        $this->assertObjectHasAttribute('microseconds', $dt);

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->assertSame(123456, $dt->getMicroseconds());
    }

    /**
     * @covers alroniks\dtms\DateTime::format
     */
    public function testFormat()
    {
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->assertSame('08.08.2015 10:10:10.123456', $dt->format('d.m.Y H:i:s.u'));
        $this->assertSame('08.08.2015 10:10:10', $dt->format('d.m.Y H:i:s'));
        $this->assertSame('1439028610.123456', $dt->format('U.u'));
    }

    /**
     * @covers alroniks\dtms\DateTime::__toString
     */
    public function testToString()
    {
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->assertSame('2015-08-08T10:10:10.123456Z', '' . $dt);

        $dt->setMicroseconds(456);
        $this->assertSame('2015-08-08T10:10:10.000456Z', '' . $dt);

        $dt->setMicroseconds(101010);
        $this->assertSame('2015-08-08T10:10:10.101010Z', '' . $dt);
    }
}
