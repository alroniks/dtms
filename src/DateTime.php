<?php

namespace alroniks\dtms;

use DateTimeZone;

class DateTime extends \DateTime
{
    /**
     * Improved ISO8601 format string with support of microseconds.
     */
    const ISO8601 = 'Y-m-d\TH:i:s.u\Z';

    /**
     * @var int Current number of microseconds.
     */
    public $microseconds;

    /**
     * Sets microseconds data to object.
     *
     * @param $microseconds
     */
    public function setMicroseconds($microseconds)
    {
        $this->microseconds = intval($microseconds);
    }

    /**
     * Gets microseconds data from object
     *
     * @param boolean $asSeconds If defined, microseconds will be converted to seconds with fractions
     * @return string
     */
    public function getMicroseconds($asSeconds = false)
    {
        if ($asSeconds) {
            return round($this->microseconds * 1/1e6, 6);
        }

        return intval($this->microseconds);
    }

    /**
     * Parse a string into a new DateTime object according to the specified format
     *
     * @param string $format
     * @param string $time
     * @param null $timezone
     * @return DateTime|\DateTime
     */
    public static function createFromFormat($format, $time, $timezone = null)
    {
        if ($timezone === null) {
            $timezone = new DateTimeZone(date_default_timezone_get());
        }

        $datetime = \DateTime::createFromFormat($format, $time, $timezone);

        return new self($datetime->format(DateTime::ISO8601), $timezone);
    }

    /**
     * Instantiates custom DateTime object with support of microseconds.
     *
     * @param string $time
     * @param DateTimeZone|null $timezone
     */
    public function __construct($time = 'now', \DateTimeZone $timezone = null)
    {
        if ($timezone === null) {
            $timezone = new DateTimeZone(date_default_timezone_get());
        }

        $nativeTime = new \DateTime($time, $timezone);
        list($u, $s) = $time == 'now'
            ? explode(' ', microtime())
            : array(
                $nativeTime->format('u') / 1e6,
                $nativeTime->getTimestamp()
            );

        $time = \DateTime::createFromFormat('U.u', join('.', array($s, sprintf('%6f', $u) * 1e6)));
        $this->microseconds = $time->format('u') ?: 0;

        return parent::__construct($time->format(static::ISO8601), $timezone);
    }

    public function getTimestamp($inMicroseconds = false)
    {
        $timestamp = parent::getTimestamp();

        if ($inMicroseconds) {
            $timestamp += $this->getMicroseconds(true);
        }

        return $timestamp;
    }

    public function add($interval)
    {
        if ($interval instanceof DateInterval) {
            $this->modifyMicroseconds(intval($interval->u), $interval->invert);
        }

        return parent::add($interval);
    }

    public function sub($interval)
    {
        if ($interval instanceof DateInterval) {
            $this->modifyMicroseconds(intval($interval->u), !$interval->invert);
        }

        return parent::sub($interval);
    }

    protected function modifyMicroseconds($microseconds, $invert)
    {
        if ($invert) {
            $microseconds *= -1;
        }

        $diff = $this->getMicroseconds() + $microseconds;

        // todo: refactoring - move it to separated method, because code duplicated
        if ($diff > 1e6) {
            $this->setMicroseconds($diff - 1e6);
            parent::modify("+1 seconds"); // +1 sec
        } else {
            if ($diff < 0) {
                parent::modify("-1 seconds"); // -1 sec
                $this->setMicroseconds(1e6 + $diff);
            } else {
                $this->setMicroseconds($diff);
            }

        }
    }

    public function modify($modify)
    {
        // add support of microseconds

        return parent::modify($modify);
    }

    public function diff($datetime, $absolute = false)
    {
        $d1 = $this;
        $d2 = $datetime instanceof \DateTime ? new self($datetime) : clone $datetime;

        $diff = new DateInterval('PT0S');
        foreach (get_object_vars(parent::diff($datetime)) as $property => $value) {
            $diff->{$property} = $value;
        }

        $udiff = $d1->getMicroseconds() - $d2->getMicroseconds();

        if ($udiff > 1e6) {
            $udiff = $udiff - 1e6;
            $diff->s++;
        } else {
            if ($udiff < 0) {
                $udiff = 1e6 + $udiff;
                $diff->s--;
            }
        }

        $diff->u = $udiff;

        if ($diff->s < 0) {
            $diff->invert = !$diff->invert;
            $diff->s = abs($diff->s);
        }

//        $diff->invert = $diff->invert && ($d1->getTimestamp(true) < $d2->getTimestamp(true));

        return $diff;
    }

    /**
     * Returns date formatted according to given format.
     *
     * @param string $format
     * @return string
     */
    public function format($format)
    {
        $format = str_replace('u', sprintf('%06d', $this->microseconds), $format);

        return parent::format($format);
    }

    /**
     * Converts DateTime object to string using ISO8601 format.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format(static::ISO8601);
    }
}
