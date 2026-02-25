<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceRequestStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public ServiceRequest $serviceRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Service Request Update: ' . $this->serviceRequest->reference_code)
            ->line('Your service request status has been updated to: ' . $this->serviceRequest->status);

        if ($this->serviceRequest->admin_note) {
            $mail->line('Note: ' . $this->serviceRequest->admin_note);
        }

        return $mail
            ->line('Reference Code: ' . $this->serviceRequest->reference_code)
            ->line('Thank you for using our services.');
    }
}
