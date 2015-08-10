<?php

namespace alroniks\dtms;

class DateInterval extends \DateInterval
{
    /**
     * Number of microseconds
     * @var int
     */
    public $u;

    /**
     * @param string $interval_spec
     */
    public function __construct($interval_spec)
    {
        if (preg_match('/\.([0-9]{1,6})/', $interval_spec, $matches)) {
            $u = intval(str_pad(array_pop($matches), 6, 0, STR_PAD_RIGHT));
            $this->u = $u;
            $interval_spec = str_replace(array_shift($matches), '', $interval_spec);
        }

        return parent::__construct($interval_spec);
    }

    /**
     * @param $format
     * @return mixed|string
     */
    public function format($format)
    {
        $formatted = parent::format($format);

        if ($this instanceof DateInterval
            && property_exists($this, 'u')
        ) {
            $formatted = preg_replace(
                '/([0-9]{1,2})S/',
                "$1." . str_pad($this->u, 6, 0, STR_PAD_LEFT) . 'S',
                $formatted
            );
        }

        return $formatted;
    }

    /**
     * @param string $time
     * @return \DateInterval
     */
    public static function createFromDateString($time)
    {
        $interval = parent::createFromDateString($time);
        $interval->u = 0; // should be implemented

        return $interval;
    }

}
