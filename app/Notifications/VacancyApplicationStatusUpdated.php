<?php

namespace App\Notifications;

use App\Models\VacancyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VacancyApplicationStatusUpdated extends Notification implements ShouldQueue
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
        $statusLabel = $this->application->status_label;

        $mail = (new MailMessage())
            ->subject(__('Your vacancy application status has been updated'))
            ->greeting(__('Application status update'))
            ->line(__('The status of your application for :vacancy is now :status.', [
                'vacancy' => $vacancyTitle,
                'status' => $statusLabel,
            ]));

        if ($this->application->admin_note) {
            $mail->line(__('Admin note: :note', ['note' => $this->application->admin_note]));
        }

        $mail->line(__('Thank you for your interest. We will keep you posted on further developments.'));

        return $mail;
    }
}
