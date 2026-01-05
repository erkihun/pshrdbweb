<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Page;
use App\Models\Setting;
use App\Services\SiteSettingsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

final class SettingsController extends Controller
{
    public function edit(SiteSettingsService $settings)
    {
        $context = $settings->all();

        $privacyPolicy = Page::where('key', 'privacy_policy')->first();

        return view('admin.settings.edit', [
            'branding' => $context['site.branding'] ?? [],
            'contact' => $context['site.contact'] ?? [],
            'notifications' => $context['site.notifications'] ?? [],
            'analytics' => $context['site.analytics'] ?? [],
            'footer' => $context['site.footer'] ?? [],
            'seo' => $context['site.seo'] ?? [],
            'privacyPolicy' => $privacyPolicy,
        ]);
    }

    public function update(UpdateSettingsRequest $request, SiteSettingsService $settings)
    {
        $validated = $request->validated();

        // Current stored paths (from cache/db)
        $currentBranding = $settings->get('site.branding', []);
        $currentLogoPath = $currentBranding['logo_path'] ?? null;
        $currentFaviconPath = $currentBranding['favicon_path'] ?? null;

        // Upload first (so we can persist new paths)
        $newLogoPath = $currentLogoPath;
        $newFaviconPath = $currentFaviconPath;

        if ($request->hasFile('logo')) {
            $newLogoPath = $this->storeFile(
                $request->file('logo'),
                is_string($currentLogoPath) ? $currentLogoPath : null
            );
        }

        if ($request->hasFile('favicon')) {
            $newFaviconPath = $this->storeFile(
                $request->file('favicon'),
                is_string($currentFaviconPath) ? $currentFaviconPath : null
            );
        }

        $branding = array_merge($currentBranding, [
            'site_name_am' => $validated['site_name_am'] ?? null,
            'site_name_en' => $validated['site_name_en'] ?? null,
        ]);

        if ($newLogoPath !== null) {
            $branding['logo_path'] = $newLogoPath;
        }

        if ($newFaviconPath !== null) {
            $branding['favicon_path'] = $newFaviconPath;
        }

        $contact = [
            'address_am' => $validated['address_am'] ?? null,
            'address_en' => $validated['address_en'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'working_hours_am' => $validated['working_hours_am'] ?? null,
            'working_hours_en' => $validated['working_hours_en'] ?? null,
        ];

        $notifications = [
            'admin_email' => $validated['admin_email'] ?? null,
            'enable_email' => $request->boolean('enable_email'),
            'enable_sms' => $request->boolean('enable_sms'),
        ];

        $analytics = [
            'enabled' => $request->boolean('analytics_enabled'),
        ];

        $footer = [
            'quick_links' => $this->normalizeLinks($validated['quick_links'] ?? []),
            'social_links' => $this->normalizeLinks($validated['social_links'] ?? []),
        ];

        Setting::updateOrCreate(['key' => 'site.branding'], ['value' => $branding]);
        Setting::updateOrCreate(['key' => 'site.contact'], ['value' => $contact]);
        Setting::updateOrCreate(['key' => 'site.notifications'], ['value' => $notifications]);
        Setting::updateOrCreate(['key' => 'site.analytics'], ['value' => $analytics]);
        Setting::updateOrCreate(['key' => 'site.footer'], ['value' => $footer]);
        Setting::updateOrCreate(['key' => 'site.seo'], ['value' => [
            'description_am' => $validated['description_am'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'google_verification' => $validated['google_verification'] ?? null,
            'bing_verification' => $validated['bing_verification'] ?? null,
        ]]);

        $privacyPolicy = Page::where('key', 'privacy_policy')->first();
        Page::updateOrCreate(
            ['key' => 'privacy_policy'],
            [
                'title_am' => $validated['privacy_title_am'] ?? $privacyPolicy?->title_am ?? __('public.privacy_page.title'),
                'title_en' => $validated['privacy_title_en'] ?? $privacyPolicy?->title_en ?? __('public.privacy_page.title'),
                'body_am' => $validated['privacy_body_am'] ?? $privacyPolicy?->body_am ?? implode("\n\n", (array) __('public.privacy_page.body')),
                'body_en' => $validated['privacy_body_en'] ?? $privacyPolicy?->body_en ?? implode("\n\n", (array) __('public.privacy_page.body')),
                'is_published' => true,
            ]
        );
        Cache::forget('public.privacy-policy');

        $settings->clearCache();

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', __('common.messages.settings_saved'));
    }

    private function normalizeLinks(array $links): array
    {
        return collect($links)
            ->map(function ($link): array {
                $link = is_array($link) ? $link : [];

                return [
                    'label_am' => trim((string) ($link['label_am'] ?? '')),
                    'label_en' => trim((string) ($link['label_en'] ?? '')),
                    'url' => trim((string) ($link['url'] ?? '')),
                ];
            })
            ->filter(fn (array $link) => filled($link['url']))
            ->values()
            ->toArray();
    }

    private function storeFile(UploadedFile $file, ?string $existingPath): string
    {
        $path = $file->store('branding', 'public');

        if ($existingPath && Storage::disk('public')->exists($existingPath)) {
            Storage::disk('public')->delete($existingPath);
        }

        return $path;
    }
}
