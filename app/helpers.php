<?php

use App\Support\EthiopianCalendar;

if (! function_exists('ethiopian_date')) {
    /**
     * Format a date using the Ethiopian calendar when the current locale is Amharic (am).
     * Falls back to a Gregorian format otherwise.
     *
     * @param  mixed  $value
     * @param  string  $pattern
     * @param  string  $timezone
     * @param  string|null  $locale
     * @param  string  $fallbackPattern
     * @return string
     */
    function ethiopian_date($value, string $pattern = 'EEEE, dd MMMM yyyy h:mm a', string $timezone = 'Africa/Addis_Ababa', ?string $locale = null, string $fallbackPattern = 'F d, Y'): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale !== 'am') {
            try {
                $date = $value instanceof \Carbon\CarbonInterface
                    ? $value
                    : \Carbon\Carbon::parse($value);

                return $date->setTimezone(new \DateTimeZone($timezone))->format($fallbackPattern);
            } catch (\Throwable $e) {
                return '';
            }
        }

        return EthiopianCalendar::format($value, $pattern, $timezone);
    }
}
