<?php

namespace App\Notifications;

use App\Models\VacancyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public VacancyApplication $application
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__('Vacancy application status updated'))
            ->markdown('emails.applications.status-updated', [
                'application' => $this->application,
                'vacancy' => $this->application->vacancy,
                'statusLabel' => $this->application->status_label,
                'dashboardUrl' => route('applicant.dashboard'),
                'appName' => config('app.name'),
                'logoUrl' => null,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'application_status_updated',
            'vacancy_title' => $this->application->vacancy?->title,
            'reference_code' => $this->application->reference_code,
            'status' => $this->application->status,
            'status_label' => $this->application->status_label,
            'admin_note' => $this->application->admin_note,
            'dashboard_url' => route('applicant.dashboard'),
        ];
    }
}
