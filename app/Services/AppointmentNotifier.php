<?php

namespace App\Services;

use App\Mail\AppointmentBookedMail;
use App\Mail\AppointmentReminderMail;
use App\Mail\AppointmentStatusMail;
use App\Models\Appointment;
use App\Services\SmsService;
use Illuminate\Support\Facades\Mail;

class AppointmentNotifier
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function sendBookingNotification(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'slot']);

        if ($appointment->email) {
            Mail::to($appointment->email)->queue(new AppointmentBookedMail($appointment));
        }

        $this->sendSms($appointment, 'appointment_booked');
    }

    public function sendStatusNotification(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'slot']);

        if ($appointment->email) {
            Mail::to($appointment->email)->queue(new AppointmentStatusMail($appointment));
        }

        $this->sendSms($appointment, 'appointment_status');
    }

    public function sendReminderNotification(Appointment $appointment): void
    {
        $appointment->loadMissing(['service', 'slot']);

        if ($appointment->email) {
            Mail::to($appointment->email)->queue(new AppointmentReminderMail($appointment));
        }

        $this->sendSms($appointment, 'appointment_reminder');
    }

    private function sendSms(Appointment $appointment, string $templateKey): void
    {
        if (! $appointment->phone) {
            return;
        }

        $template = $this->smsService->template($templateKey);

        if (! $template) {
            return;
        }

        $message = $this->formatTemplate($template, $appointment);

        $this->smsService->queue($appointment->phone, $message, $templateKey, $appointment->id);
    }

    private function formatTemplate(string $template, Appointment $appointment): string
    {
        $data = [
            '{reference_code}' => $appointment->reference_code,
            '{service}' => $appointment->service->display_name,
            '{status}' => __('common.status.' . $appointment->status),
            '{date}' => $appointment->slot->starts_at->format('F j, Y'),
            '{time}' => $appointment->slot->starts_at->format('g:i A'),
        ];

        return strtr($template, $data);
    }
}
