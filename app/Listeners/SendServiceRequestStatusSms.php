<?php

namespace App\Listeners;

use App\Events\ServiceRequestStatusUpdated;
use App\Services\SmsService;

class SendServiceRequestStatusSms
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function handle(ServiceRequestStatusUpdated $event): void
    {
        $request = $event->serviceRequest;
        if (! $request->phone) {
            return;
        }

        $message = $this->formatMessage($request->reference_code, $request->status, $request->admin_note);
        $this->smsService->queue($request->phone, $message, 'service_request', $request->id);
    }

    private function formatMessage(string $referenceCode, string $status, ?string $note): string
    {
        $settings = \App\Models\Setting::where('key', 'sms.settings')->first()?->value ?? [];
        $template = $settings['template_service_request_status'] ?? config('services.sms.templates.service_request_status', 'Your request {reference_code} status: {status}.');

        $message = str_replace(
            ['{reference_code}', '{status}'],
            [$referenceCode, $status],
            $template
        );

        if ($note) {
            $message .= ' ' . $note;
        }

        return $message;
    }
}
