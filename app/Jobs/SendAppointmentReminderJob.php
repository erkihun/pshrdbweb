<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\AppointmentNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SendAppointmentReminderJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(AppointmentNotifier $notifier): void
    {
        $start = Carbon::now()->addDay()->subMinutes(30);
        $end = Carbon::now()->addDay()->addMinutes(30);

        Appointment::with(['slot', 'service'])
            ->whereNull('reminder_sent_at')
            ->whereIn('status', ['booked', 'confirmed'])
            ->whereHas('slot', function ($query) use ($start, $end) {
                $query->whereBetween('starts_at', [$start, $end]);
            })
            ->chunkById(50, function ($appointments) use ($notifier) {
                foreach ($appointments as $appointment) {
                    $notifier->sendReminderNotification($appointment);
                    $appointment->update(['reminder_sent_at' => now()]);
                }
            });
    }
}
