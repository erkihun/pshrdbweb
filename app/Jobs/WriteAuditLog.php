<?php

namespace App\Jobs;

use App\Models\AuditLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WriteAuditLog implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly ?int $userId,
        public readonly string $action,
        public readonly string $entityType,
        public readonly ?string $entityId,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
        public readonly array $metadata
    ) {}

    public function handle(): void
    {
        AuditLog::create([
            'user_id' => $this->userId,
            'action' => $this->action,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'metadata' => $this->metadata,
        ]);
    }
}
