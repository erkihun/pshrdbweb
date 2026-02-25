<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\HomeSection;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HomepageController extends Controller
{
    public function edit()
    {
        $defaults = app(\App\Http\Controllers\HomepageController::class)->defaultSections();
        $stored = Setting::whereIn('key', HomeSection::keys())
            ->get()
            ->keyBy('key')
            ->map(fn ($setting) => $setting->value ?? [])
            ->toArray();

        $sections = array_replace_recursive($defaults, $stored);

        return view('admin.homepage.edit', compact('sections'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero.title' => ['nullable', 'string', 'max:255'],
            'hero.subtitle' => ['nullable', 'string', 'max:600'],
            'hero.cta_text' => ['nullable', 'string', 'max:100'],
            'hero.cta_url' => ['nullable', 'string', 'max:255'],
            'hero.background_image' => ['nullable', 'image', 'max:4096'],
            'services.title' => ['nullable', 'string', 'max:255'],
            'services.items' => ['array'],
            'services.items.*.title' => ['nullable', 'string', 'max:255'],
            'services.items.*.description' => ['nullable', 'string', 'max:500'],
            'services.icons' => ['array'],
            'services.icons.*' => ['nullable', 'image', 'max:2048'],
            'news.title' => ['nullable', 'string', 'max:255'],
            'news.items' => ['array'],
            'news.items.*.title' => ['nullable', 'string', 'max:255'],
            'news.items.*.description' => ['nullable', 'string', 'max:500'],
            'news.items.*.url' => ['nullable', 'string', 'max:255'],
            'stats.items' => ['array'],
            'stats.items.*.label' => ['nullable', 'string', 'max:255'],
            'stats.items.*.value' => ['nullable', 'string', 'max:100'],
            'footer.items' => ['array'],
            'footer.items.*.label' => ['nullable', 'string', 'max:255'],
            'footer.items.*.url' => ['nullable', 'string', 'max:255'],
        ]);

        $hero = [
            'title' => $data['hero']['title'] ?? null,
            'subtitle' => $data['hero']['subtitle'] ?? null,
            'cta_text' => $data['hero']['cta_text'] ?? null,
            'cta_url' => $data['hero']['cta_url'] ?? null,
        ];

        $services = [
            'title' => $data['services']['title'] ?? null,
            'items' => $data['services']['items'] ?? [],
        ];

        $news = [
            'title' => $data['news']['title'] ?? null,
            'items' => $data['news']['items'] ?? [],
        ];

        $stats = [
            'items' => $data['stats']['items'] ?? [],
        ];

        $footer = [
            'items' => $data['footer']['items'] ?? [],
        ];

        $heroSetting = Setting::firstWhere('key', HomeSection::HERO->value);
        if ($request->hasFile('hero.background_image')) {
            $hero['background_image'] = $this->storeImage($request->file('hero.background_image'), $heroSetting?->value['background_image'] ?? null);
        } else {
            $hero['background_image'] = $heroSetting?->value['background_image'] ?? null;
        }

        $servicesSetting = Setting::firstWhere('key', HomeSection::SERVICES_HIGHLIGHT->value);
        $serviceIcons = $servicesSetting?->value['items'] ?? [];
        foreach ($request->file('services.icons', []) as $index => $file) {
            $existing = $serviceIcons[$index]['icon'] ?? null;
            $services['items'][$index]['icon'] = $this->storeImage($file, $existing);
        }
        foreach ($services['items'] as $index => $item) {
            if (! isset($item['icon'])) {
                $services['items'][$index]['icon'] = $serviceIcons[$index]['icon'] ?? null;
            }
        }

        Setting::updateOrCreate(['key' => HomeSection::HERO->value], ['value' => $hero]);
        Setting::updateOrCreate(['key' => HomeSection::SERVICES_HIGHLIGHT->value], ['value' => $services]);
        Setting::updateOrCreate(['key' => HomeSection::NEWS_HIGHLIGHT->value], ['value' => $news]);
        Setting::updateOrCreate(['key' => HomeSection::STATS->value], ['value' => $stats]);
        Setting::updateOrCreate(['key' => HomeSection::FOOTER_LINKS->value], ['value' => $footer]);

        Cache::forget('home.settings');

        return redirect()
            ->route('admin.homepage.edit')
            ->with('success', 'Homepage updated successfully.');
    }

    private function storeImage($file, ?string $existingPath): ?string
    {
        if (! $file) {
            return $existingPath;
        }

        if ($existingPath) {
            Storage::disk('public')->delete($existingPath);
        }

        return $file->store('home', 'public');
    }
}
