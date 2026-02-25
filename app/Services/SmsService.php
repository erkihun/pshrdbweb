<?php

namespace App\Services;

use App\Contracts\SmsProvider;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Cache;

class SmsService
{
    public function __construct(private SmsProvider $provider)
    {
    }

    public function queue(string $to, string $message, string $contextType, ?string $contextId = null): void
    {
        dispatch(new \App\Jobs\SendSmsJob($to, $message, $contextType, $contextId));
    }

    public function sendNow(string $to, string $message, string $contextType, ?string $contextId = null): bool
    {
        $settings = $this->settings();

        if (! $settings['enabled']) {
            return false;
        }

        $to = trim($to);
        if ($to === '') {
            return false;
        }

        if ($this->isRateLimited($to)) {
            $this->log($to, $message, $contextType, $contextId, 'failed', ['reason' => 'rate_limited']);
            return false;
        }

        $sent = false;
        $response = null;

        try {
            $sent = $this->provider->send($to, $message);
            $response = ['sent' => $sent];
        } catch (\Throwable $e) {
            $response = ['error' => $e->getMessage()];
        }

        $this->log($to, $message, $contextType, $contextId, $sent ? 'sent' : 'failed', $response);

        return $sent;
    }

    private function isRateLimited(string $phone): bool
    {
        $key = 'sms:rate:' . $phone;
        $count = Cache::increment($key);
        Cache::put($key, $count, now()->addHour());

        return $count > 5;
    }

    private function log(string $to, string $message, string $contextType, ?string $contextId, string $status, ?array $response = null): void
    {
        SmsLog::create([
            'phone' => $to,
            'message' => $message,
            'context_type' => $contextType,
            'context_id' => $contextId,
            'status' => $status,
            'provider_response' => $response,
            'sent_at' => now(),
        ]);
    }

    private function settings(): array
    {
        $cacheKey = 'sms:settings';

        return Cache::remember($cacheKey, 300, function () {
            $stored = \App\Models\Setting::where('key', 'sms.settings')->first()?->value ?? [];

            return array_merge([
                'enabled' => config('services.sms.enabled'),
                'template_ticket_created' => config('services.sms.templates.ticket_created'),
                'template_ticket_replied' => config('services.sms.templates.ticket_replied'),
                'template_service_request_status' => config('services.sms.templates.service_request_status'),
                'template_appointment_booked' => config('services.sms.templates.appointment_booked'),
                'template_appointment_status' => config('services.sms.templates.appointment_status'),
                'template_appointment_reminder' => config('services.sms.templates.appointment_reminder'),
            ], $stored);
        });
    }

    public function template(string $key): ?string
    {
        $settings = $this->settings();

        $mapping = [
            'ticket_created' => 'template_ticket_created',
            'ticket_replied' => 'template_ticket_replied',
            'service_request_status' => 'template_service_request_status',
            'document_request_submitted' => 'template_document_request_submitted',
            'document_request_status' => 'template_document_request_status',
            'appointment_booked' => 'template_appointment_booked',
            'appointment_status' => 'template_appointment_status',
            'appointment_reminder' => 'template_appointment_reminder',
        ];

        $settingKey = $mapping[$key] ?? 'template_' . $key;

        return $settings[$settingKey] ?? config("services.sms.templates.{$key}");
    }
}
