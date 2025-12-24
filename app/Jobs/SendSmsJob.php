<?php

namespace App\Jobs;

use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $to,
        public string $message,
        public string $contextType,
        public ?string $contextId = null,
    ) {
    }

    public function handle(SmsService $smsService): void
    {
        $smsService->sendNow($this->to, $this->message, $this->contextType, $this->contextId);
    }
}
