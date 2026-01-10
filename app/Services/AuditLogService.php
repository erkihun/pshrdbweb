<?php

namespace App\Services;

use App\Jobs\WriteAuditLog;
use App\Models\AuditLog;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuditLogService
{
    public static function log(string $action, string $entityType, ?string $entityId = null, array $metadata = []): void
    {
        $metadata = static::sanitizeMetadata($metadata);
        $request = request();

        try {
            WriteAuditLog::dispatch(
                Auth::id(),
                $action,
                $entityType,
                $entityId,
                $request?->ip(),
                $request?->userAgent(),
                $metadata
            );
        } catch (\Throwable $exception) {
            report($exception);
            static::writeNow($action, $entityType, $entityId, $metadata, $request?->ip(), $request?->userAgent());
        }
    }

    private static function writeNow(
        string $action,
        string $entityType,
        ?string $entityId,
        array $metadata,
        ?string $ipAddress,
        ?string $userAgent
    ): void {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'metadata' => $metadata,
        ]);
    }

    private static function sanitizeMetadata(array $metadata): array
    {
        return collect($metadata)->map(function ($value) {
            if (is_array($value)) {
                return static::sanitizeMetadata($value);
            }

            if (! is_string($value)) {
                return $value;
            }

            $value = static::maskEmails($value);
            $value = static::maskPhones($value);

            return $value;
        })->all();
    }

    private static function maskEmails(string $value): string
    {
        return preg_replace_callback('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', function ($matches) {
            $email = $matches[0];
            [$name, $domain] = explode('@', $email);
            $masked = Str::substr($name, 0, 2) . str_repeat('*', max(strlen($name) - 2, 0));

            return $masked . '@' . $domain;
        }, $value);
    }

    private static function maskPhones(string $value): string
    {
        return preg_replace_callback('/\+?\d[\d\s\-().]{6,}\d/', function ($matches) {
            $phone = preg_replace('/\D+/', '', $matches[0]);
            if (strlen($phone) < 7) {
                return $matches[0];
            }

            $visible = substr($phone, -2);
            $masked = str_repeat('*', max(strlen($phone) - 2, 0)) . $visible;

            return $masked;
        }, $value);
    }
}
