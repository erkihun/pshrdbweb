<?php

namespace App\Notifications;

use App\Models\VacancyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VacancyApplicationWithdrawn extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public VacancyApplication $application
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $vacancyTitle = $this->application->vacancy?->title ?? __('Vacancy');
        $applicantName = $this->application->full_name ?: __('Applicant');

        return (new MailMessage())
            ->subject(__('Vacancy application withdrawn'))
            ->greeting(__('Vacancy application update'))
            ->line(__('The applicant :name has withdrawn their application for :vacancy.', [
                'name' => $applicantName,
                'vacancy' => $vacancyTitle,
            ]))
            ->line(__('Reference code: :code', ['code' => $this->application->id]));
    }
}
