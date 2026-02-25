<?php

namespace App\Support;

use DateTimeZone;
use IntlDateFormatter;
use Carbon\CarbonInterface;
use Carbon\Carbon;

class EthiopianCalendar
{
    public static function format($value, string $pattern = 'EEEE, dd MMMM yyyy h:mm a', string $timezone = 'Africa/Addis_Ababa', string $locale = 'am_ET@calendar=ethiopic'): string
    {
        if (empty($value)) {
            return '';
        }

        $date = $value instanceof CarbonInterface
            ? $value
            : Carbon::parse($value);

        $date = $date->copy()->setTimezone(new DateTimeZone($timezone));

        $formatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::SHORT,
            $timezone,
            IntlDateFormatter::TRADITIONAL,
            $pattern
        );

        return $formatter->format($date);
    }
}

