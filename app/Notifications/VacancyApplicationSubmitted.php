<?php

namespace App\Notifications;

use App\Models\VacancyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VacancyApplicationSubmitted extends Notification implements ShouldQueue
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

        return (new MailMessage())
            ->subject(__('Vacancy application received'))
            ->greeting(__('Thank you for applying'))
            ->line(__('We have received your application for :vacancy.', ['vacancy' => $vacancyTitle]))
            ->line(__('Our recruitment team will review your submission and contact you if there are any updates.'))
            ->line(__('Reference code: :code', ['code' => $this->application->id]))
            ->line(__('You can expect a response within 5 working days.'));
    }
}
