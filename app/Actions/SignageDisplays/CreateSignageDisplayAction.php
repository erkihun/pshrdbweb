<?php

declare(strict_types=1);

namespace App\Actions\SignageDisplays;

use App\Models\SignageDisplay;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class CreateSignageDisplayAction
{
    public function execute(array $data): SignageDisplay
    {
        return SignageDisplay::create(
            $this->normalize($data)
                + ['created_by' => $data['created_by'] ?? auth()->id()]
        );
    }

    private function normalize(array $data, ?SignageDisplay $display = null): array
    {
        return [
            'signage_template_id' => $data['signage_template_id'],
            'title_am' => $data['title_am'] ?? null,
            'title_en' => $data['title_en'] ?? null,
            'slug' => $this->buildSlug($data['slug'] ?? '', $display, $data['title_en'] ?? $data['title_am'] ?? ''),
            'payload' => $this->decodeJson($data['payload'] ?? null),
            'refresh_seconds' => (int) ($data['refresh_seconds'] ?? 60),
            'is_published' => filter_var($data['is_published'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'published_at' => $this->normalizeTimestamp($data['published_at'] ?? null),
        ];
    }

    private function buildSlug(string $value, ?SignageDisplay $display, string $fallback): string
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

    private function slugExists(string $slug, ?SignageDisplay $display): bool
    {
        return SignageDisplay::where('slug', $slug)
            ->when($display, fn ($query) => $query->where('uuid', '!=', $display->uuid))
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
