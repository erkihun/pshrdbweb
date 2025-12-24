<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Services\SmsService;

class SendTicketCreatedSms
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function handle(TicketCreated $event): void
    {
        $ticket = $event->ticket;
        if (! $ticket->phone) {
            return;
        }

        $message = $this->formatMessage($ticket->reference_code, $ticket->subject);
        $this->smsService->queue($ticket->phone, $message, 'ticket', $ticket->id);
    }

    private function formatMessage(string $referenceCode, string $subject): string
    {
        $settings = \App\Models\Setting::where('key', 'sms.settings')->first()?->value ?? [];
        $template = $settings['template_ticket_created'] ?? config('services.sms.templates.ticket_created', 'Your ticket {reference_code} has been created.');

        return str_replace(
            ['{reference_code}', '{subject}'],
            [$referenceCode, $subject],
            $template
        );
    }
}
