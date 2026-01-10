<?php

namespace App\Support;

class StorageHealth
{
    public static function hasMinimumFreeSpace(): bool
    {
        $minFreeMb = (int) config('filesystems.upload_min_free_mb', 200);
        $freeBytes = @disk_free_space(storage_path());
        if ($freeBytes === false) {
            return false;
        }

        return $freeBytes >= ($minFreeMb * 1024 * 1024);
    }

    public static function snapshot(): array
    {
        $total = @disk_total_space(storage_path()) ?: 0;
        $free = @disk_free_space(storage_path()) ?: 0;

        return [
            'total_bytes' => $total,
            'free_bytes' => $free,
            'min_free_mb' => (int) config('filesystems.upload_min_free_mb', 200),
        ];
    }
}
