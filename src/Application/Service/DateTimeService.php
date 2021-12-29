<?php

namespace App\Application\Service;

use DateInterval;

class DateTimeService
{
    private const PERIOD = 'P';
    private const TIME = 'T';

    public static function createFactory(): self
    {
        return new self();
    }

    /**
     * @see https://www.php.net/manual/en/dateinterval.construct.php
     */
    public function getDateInterval(string $period, string $time = '')
    {
        $period = self::PERIOD . $period;
        $time = $time ? self::TIME . $time : '';

        return new DateInterval($period . $time);
    }
}
