<?php

declare(strict_types=1);

namespace App\Actions\SignageDisplays;

use App\Models\SignageDisplay;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class UpdateSignageDisplayAction
{
    public function execute(SignageDisplay $display, array $data): SignageDisplay
    {
        $display->update($this->normalize($data, $display));

        return $display;
    }

    private function normalize(array $data, SignageDisplay $display): array
    {
        return [
            'signage_template_id' => $data['signage_template_id'] ?? $display->signage_template_id,
            'title_am' => $data['title_am'] ?? null,
            'title_en' => $data['title_en'] ?? null,
            'slug' => $this->buildSlug($data['slug'] ?? '', $display, $data['title_en'] ?? $data['title_am'] ?? $display->title_en ?? ''),
            'payload' => $this->decodeJson($data['payload'] ?? null),
            'refresh_seconds' => (int) ($data['refresh_seconds'] ?? $display->refresh_seconds),
            'is_published' => filter_var($data['is_published'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'published_at' => $this->normalizeTimestamp($data['published_at'] ?? null),
        ];
    }

    private function buildSlug(string $value, SignageDisplay $display, string $fallback): string
    {
        $candidate = Str::slug($value !== '' ? $value : $fallback);

        if ($candidate === '') {
            $candidate = (string) Str::uuid();
        }

        $slug = $candidate;
        $counter = 1;

        while ($this->slugExists($slug, $display)) {
            $slug = $candidate . '-' . $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, SignageDisplay $display): bool
    {
        return SignageDisplay::where('slug', $slug)
            ->where('uuid', '!=', $display->uuid)
            ->exists();
    }

    private function decodeJson(?string $value): ?array
    {
        if ($value === null || $value === '') {
            return null;
        }

        return json_decode($value, true);
    }

    private function normalizeTimestamp(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return Carbon::parse($value)->toDateTimeString();
    }
}
