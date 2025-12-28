<?php

namespace App\Console;

use App\Jobs\CleanupOldExportsJob;
use App\Jobs\SendAppointmentReminderJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new SendAppointmentReminderJob())->hourly();
        $schedule->job(new CleanupOldExportsJob())->daily();
        $schedule->command('analytics:build-daily-stats')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
