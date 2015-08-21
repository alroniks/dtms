<?php

namespace alroniks\dtms\Test;

use alroniks\dtms\DateInterval;
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

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
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
     * @covers alroniks\dtms\DateTime::getTimestampWithMicroseconds
     */
    public function testGetTimestampWithMicroseconds()
    {
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->assertSame(1439028610 + 123456 / 1e6, $dt->getTimestampWithMicroseconds());
    }

    /**
     * @covers alroniks\dtms\DateTime::addMicroseconds
     */
    public function testAddMicroseconds()
    {
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'addMicroseconds', array(0));
        $this->assertEquals('1439028610.123456', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'addMicroseconds', array(123456));
        $this->assertEquals('1439028610.246912', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'addMicroseconds', array(999999));
        $this->assertEquals('1439028611.123455', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'addMicroseconds', array(876544));
        $this->assertEquals('1439028611.000000', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'addMicroseconds', array(1876544));
        $this->assertEquals('1439028612.000000', $dt->format('U.u'));

        $this->setExpectedException(
            'InvalidArgumentException', 'Value of microseconds should be positive.'
        );
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'addMicroseconds', array(-111111));
    }

    /**
     * @covers alroniks\dtms\DateTime::subMicroseconds
     */
    public function testSubMicroseconds()
    {
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'subMicroseconds', array(0));
        $this->assertEquals('1439028610.123456', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'subMicroseconds', array(12345));
        $this->assertEquals('1439028610.111111', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'subMicroseconds', array(654321));
        $this->assertEquals('1439028609.469135', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'subMicroseconds', array(123456));
        $this->assertEquals('1439028610.000000', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'subMicroseconds', array(1123456));
        $this->assertEquals('1439028609.000000', $dt->format('U.u'));

        $this->setExpectedException(
            'InvalidArgumentException', 'Value of microseconds should be positive.'
        );
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $this->invokeMethod($dt, 'subMicroseconds', array(-111111));
    }

    /**
     * @covers alroniks\dtms\DateTime::add
     */
    public function testAdd()
    {
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->add(new DateInterval('PT0.000000S'));
        $this->assertEquals('1439028610.123456', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->add(new DateInterval('PT1.123456S'));
        $this->assertEquals('1439028611.246912', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->add(new DateInterval('PT1.999999S'));
        $this->assertEquals('1439028612.123455', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->add(new DateInterval('PT1.876544S'));
        $this->assertEquals('1439028612.000000', $dt->format('U.u'));


        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->add(new DateInterval('-PT0.000000S'));
        $this->assertEquals('1439028610.123456', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->add(new DateInterval('-PT1.123456S'));
        $this->assertEquals('1439028609.000000', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->add(new DateInterval('-PT1.999999S'));
        $this->assertEquals('1439028608.123457', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->add(new DateInterval('-PT1.876544S'));
        $this->assertEquals('1439028608.246912', $dt->format('U.u'));
    }

    /**
     * @covers alroniks\dtms\DateTime::sub
     */
    public function testSub()
    {
        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->sub(new DateInterval('PT0.000000S'));
        $this->assertEquals('1439028610.123456', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->sub(new DateInterval('PT1.123456S'));
        $this->assertEquals('1439028609.000000', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->sub(new DateInterval('PT1.999999S'));
        $this->assertEquals('1439028608.123457', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->sub(new DateInterval('PT1.876544S'));
        $this->assertEquals('1439028608.246912', $dt->format('U.u'));


        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->sub(new DateInterval('-PT0.000000S'));
        $this->assertEquals('1439028610.123456', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->sub(new DateInterval('-PT1.123456S'));
        $this->assertEquals('1439028611.246912', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->sub(new DateInterval('-PT1.999999S'));
        $this->assertEquals('1439028612.123455', $dt->format('U.u'));

        $dt = new DateTime('2015-08-08 10:10:10.123456');
        $dt->sub(new DateInterval('-PT1.876544S'));
        $this->assertEquals('1439028612.000000', $dt->format('U.u'));
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
}
