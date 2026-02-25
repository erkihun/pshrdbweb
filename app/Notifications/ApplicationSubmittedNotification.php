<?php

namespace App\Notifications;

use App\Models\VacancyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification implements ShouldQueue
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
            ->subject(__('Vacancy application received'))
            ->markdown('emails.applications.submitted', [
                'application' => $this->application,
                'vacancy' => $this->application->vacancy,
                'dashboardUrl' => route('applicant.dashboard'),
                'submittedAt' => $this->application->created_at,
                'appName' => config('app.name'),
                'logoUrl' => null,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'application_submitted',
            'vacancy_title' => $this->application->vacancy?->title,
            'reference_code' => $this->application->reference_code,
            'submitted_at' => $this->application->created_at?->toIso8601String(),
            'dashboard_url' => route('applicant.dashboard'),
        ];
    }
}
