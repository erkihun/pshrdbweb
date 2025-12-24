<?php

namespace App\Services;

use App\Contracts\SmsProvider;
use Illuminate\Support\Facades\Http;

class HttpSmsProvider implements SmsProvider
{
    public function send(string $to, string $message): bool
    {
        $baseUrl = rtrim((string) config('services.sms.base_url'), '/');
        $apiKey = config('services.sms.api_key');
        $sender = config('services.sms.sender_id');

        if (! $baseUrl || ! $apiKey) {
            return false;
        }

        $response = Http::timeout(10)
            ->retry(2, 200)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])
            ->post($baseUrl . '/send', [
                'to' => $to,
                'message' => $message,
                'sender' => $sender,
            ]);

        return $response->successful();
    }
}
