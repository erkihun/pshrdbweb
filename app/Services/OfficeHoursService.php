<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;

class OfficeHoursService
{
    public function isOpen(): bool
    {
        [$start, $end] = $this->bounds();

        $now = Carbon::now(config('app.timezone'));

        return $now->between($start, $end);
    }

    public function bounds(): array
    {
        $raw = Setting::firstWhere('key', 'office.hours')?->value ?? [];

        $start = $raw['start'] ?? '08:30';
        $end = $raw['end'] ?? '17:30';
        $timezone = config('app.timezone');

        $startTime = Carbon::now($timezone)->setTimeFromTimeString($start);
        $endTime = Carbon::now($timezone)->setTimeFromTimeString($end);

        if ($endTime->lessThan($startTime)) {
            $endTime->addDay();
        }

        return [$startTime, $endTime];
    }

    public function summary(): string
    {
        [$start, $end] = $this->bounds();

        return sprintf('%s ? %s', $start->format('g:i A'), $end->format('g:i A'));
    }
}
