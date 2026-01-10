<?php

namespace App\Notifications;

use App\Models\VacancyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationWithdrawnNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public VacancyApplication $application,
        public bool $sendMail = false
    ) {
    }

    public function via($notifiable): array
    {
        return $this->sendMail ? ['mail', 'database'] : ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__('Vacancy application withdrawn'))
            ->markdown('emails.applications.withdrawn', [
                'application' => $this->application,
                'vacancy' => $this->application->vacancy,
                'dashboardUrl' => route('applicant.dashboard'),
                'appName' => config('app.name'),
                'logoUrl' => null,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'application_withdrawn',
            'vacancy_title' => $this->application->vacancy?->title,
            'reference_code' => $this->application->reference_code,
            'applicant_name' => $this->application->full_name,
        ];
    }
}
