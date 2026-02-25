<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class SiteSettingsService
{
    public const CACHE_KEY = 'site.settings';
    public const CACHE_TTL = 600; // 10 minutes

    private array $defaults = [
        'site.branding' => [
            'site_name_am' => 'በርካታ የአዲስ አበባ ግልጽ አገልግሎት',
            'site_name_en' => 'AA Public Service',
            'logo_path' => null,
            'favicon_path' => null,
        ],
        'site.contact' => [
            'address_am' => 'አራዳ ክፍለ ከተማ፣ አዲስ አበባ፣ ኢትዮጵያ',
            'address_en' => 'Arada Subcity, Addis Ababa, Ethiopia',
            'phone' => '+251 11 123 4567',
            'email' => 'info@aapublicservice.gov.et',
            'working_hours_am' => 'ሰኞ - አርብ 8:30 አ.ም. - 5:30 ከ.ም.',
            'working_hours_en' => 'Mon - Fri, 8:30 AM - 5:30 PM',
        ],
        'site.notifications' => [
            'admin_email' => 'admin@aapublicservice.gov.et',
            'enable_email' => true,
            'enable_sms' => false,
        ],
        'site.analytics' => [
            'enabled' => false,
        ],
        'site.seo' => [
            'description_am' => 'Addis Ababa Public Service portal for news, announcements, and services.',
            'description_en' => 'Digital gateway for Addis Ababa public service news, announcements, and citizen services.',
            'google_verification' => null,
            'bing_verification' => null,
        ],
        'site.footer' => [
            'quick_links' => [],
            'social_links' => [],
        ],
    ];

    public function all(): array
    {
        if (!$this->hasSettingsTable()) {
            return $this->defaults;
        }

        if (!Schema::hasTable('cache')) {
            return $this->load();
        }

        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->load();
        });
    }

    public function get(string $key, $default = null)
    {
        return data_get($this->all(), $key, $default);
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function load(): array
    {
        try {
            $stored = Setting::whereIn('key', array_keys($this->defaults))
                ->get()
                ->mapWithKeys(fn ($setting) => [$setting->key => $setting->value])
                ->toArray();
        } catch (QueryException $e) {
            return $this->defaults;
        }

        $result = [];

        foreach ($this->defaults as $key => $default) {
            $storedValue = $stored[$key] ?? [];
            $result[$key] = array_replace_recursive($default, $storedValue);
        }

        return $result;
    }

    private function hasSettingsTable(): bool
    {
        try {
            return Schema::hasTable('settings');
        } catch (QueryException $e) {
            $connection = Schema::getConnection();

            if ($connection->getDriverName() === 'sqlite') {
                $path = $connection->getConfig('database');

                if ($path && !file_exists($path)) {
                    return false;
                }
            }

            // If we couldn't talk to the database (e.g. during package discovery), assume the settings table is missing.
            return false;
        }
    }
}
