<?php

namespace App\Helper;

use DateTime;
use DateTimeZone;

class UTCConverter
{
    public static function convert(string $date, string $timezone): DateTime
    {
        $dateTime = new DateTime($date, new DateTimeZone($timezone));
        $timezone = new DateTimeZone('UTC');

        return $dateTime->setTimezone($timezone);
    }
}