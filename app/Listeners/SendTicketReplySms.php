<?php

namespace App\Listeners;

use App\Events\TicketReplied;
use App\Services\SmsService;

class SendTicketReplySms
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function handle(TicketReplied $event): void
    {
        $ticket = $event->ticket;
        if (! $ticket->phone) {
            return;
        }

        $message = $this->formatMessage($ticket->reference_code, $ticket->status, $ticket->admin_reply);
        $this->smsService->queue($ticket->phone, $message, 'ticket', $ticket->id);
    }

    private function formatMessage(string $referenceCode, string $status, ?string $reply): string
    {
        $settings = \App\Models\Setting::where('key', 'sms.settings')->first()?->value ?? [];
        $template = $settings['template_ticket_replied'] ?? config('services.sms.templates.ticket_replied', 'Your ticket {reference_code} has a new reply. Status: {status}.');

        $message = str_replace(
            ['{reference_code}', '{status}'],
            [$referenceCode, $status],
            $template
        );

        if ($reply) {
            $message .= ' ' . $reply;
        }

        return $message;
    }
}
