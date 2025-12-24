<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use App\Services\SiteSettingsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit(SiteSettingsService $settings)
    {
        $context = $settings->all();

        return view('admin.settings.edit', [
            'branding' => $context['site.branding'] ?? [],
            'contact' => $context['site.contact'] ?? [],
            'notifications' => $context['site.notifications'] ?? [],
            'analytics' => $context['site.analytics'] ?? [],
            'footer' => $context['site.footer'] ?? [],
        ]);
    }

    public function update(UpdateSettingsRequest $request, SiteSettingsService $settings)
    {
        $validated = $request->validated();

        $branding = [
            'site_name_am' => $validated['site_name_am'],
            'site_name_en' => $validated['site_name_en'],
            'logo_path' => $settings->get('site.branding.logo_path'),
            'favicon_path' => $settings->get('site.branding.favicon_path'),
        ];

        if ($request->hasFile('logo')) {
            $branding['logo_path'] = $this->storeFile(
                $request->file('logo'),
                $branding['logo_path']
            );
        }

        if ($request->hasFile('favicon')) {
            $branding['favicon_path'] = $this->storeFile(
                $request->file('favicon'),
                $branding['favicon_path']
            );
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

        $settings->clearCache();

        return redirect()->route('admin.settings.edit')->with('success', __('common.messages.settings_saved'));
    }

    private function normalizeLinks(array $links): array
    {
        return collect($links)
            ->map(function (array $link) {
                return [
                    'label_am' => trim($link['label_am'] ?? ''),
                    'label_en' => trim($link['label_en'] ?? ''),
                    'url' => trim($link['url'] ?? ''),
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
