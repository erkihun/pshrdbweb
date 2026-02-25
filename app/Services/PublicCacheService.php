<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PublicCacheService
{
    public const TTL = 600;

    public static function key(string $section, string $locale, int $page = 1): string
    {
        $version = static::version($section);

        return sprintf('public:%s:v%s:%s:%d', $section, $version, $locale, $page);
    }

    public static function version(string $section): int
    {
        $key = static::versionKey($section);

        return (int) Cache::get($key, 1);
    }

    public static function bump(string $section): void
    {
        $key = static::versionKey($section);

        if (! Cache::has($key)) {
            Cache::put($key, 2);
            return;
        }

        Cache::increment($key);
    }

    private static function versionKey(string $section): string
    {
        return 'public_cache_version:' . $section;
    }
}
