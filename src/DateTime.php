<?php

namespace alroniks\dtms;

use DateTimeZone;

class DateTime extends \DateTime
{
    const ISO8601 = 'Y-m-d\TH:i:s.u\Z';

    public  $microseconds;

    public function setMicroseconds($microcesonds)
    {
        $this->microseconds = $microcesonds;
    }

    public function getMicroseconds()
    {
        return $this->microseconds;
    }

    public function __construct($time = 'now', \DateTimeZone $timezone = null)
    {
        if ($time == 'now') {
            $micro = microtime();
            list($u, $s) = explode(' ', $micro);
            $time = \DateTime::createFromFormat('U.u', join('.', array($s, sprintf('%6f', $u) * 1e6)));
            $this->microseconds = $time->format('u');
        }

        return parent::__construct($time instanceof \DateTime ? $time->format(self::ISO8601) : $time, $timezone);
    }

    public function setTime($hour, $minute, $second = 0, $microsecond = 0)
    {
        $second += $microsecond / 1e6;

        $this->setMicroseconds($microsecond);

        return parent::setTime($hour, $minute, $second);
    }

    // add
    public function add(DateInterval $interval)
    {
        if ($interval instanceof DateInterval) {


            return parent::add($interval);

        }


    }

    protected function modifyMicroseconds($microseconds)
    {

    }

    // sub
    // modify

    // setTimestamp // with ms
    public function setTimestamp($seconds)
    {
        if (false !== ($res = filter_var($seconds, FILTER_VALIDATE_INT))) {
            return $datetime->add(new DateInterval('PT'.$res.'S'));
        }
        $timestamp = explode('.', sprintf('%6f', $seconds));
        $seconds   = (int) $timestamp[0];
        $micro     = $timestamp[1] + $datetime->format('u');
        if ($micro > 1e6) {
            $micro -= 1e6;
            $seconds++;
        }
        $dateEnd = $datetime->add(new DateInterval('PT'.$seconds.'S'));
        return new DateTimeImmutable(
            $dateEnd->format('Y-m-d H:i:s').".".sprintf('%06d', $micro),
            $datetime->getTimeZone()
        );
    }

    // getTimestamp // with ms
    public function getTimestapm()
    {
        return parent::getTimestamp();
    }

    // diff

    public static function createFromFormat($format, $time, DateTimeZone $timezone = null)
    {
        if ($timezone === null) {
            $timezone = new DateTimeZone(date_default_timezone_get());
        }

        $datetime = \DateTime::createFromFormat($format, $time, $timezone);
        $microseconds = $datetime->format('u');

        $datetime = new self($datetime);
        $datetime->setMicroseconds($microseconds);

        return $datetime;
    }

    public function __toString()
    {
        return $this->format(static::ISO8601);
    }
}
