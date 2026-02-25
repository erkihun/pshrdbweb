<?php

declare(strict_types=1);

namespace App\Actions\SignageTemplates;

use App\Models\SignageTemplate;
use Illuminate\Support\Str;

final class UpdateSignageTemplateAction
{
    public function execute(SignageTemplate $template, array $data): SignageTemplate
    {
        $template->update($this->normalize($data, $template));

        return $template;
    }

    private function normalize(array $data, SignageTemplate $template): array
    {
        return [
            'name_am' => $data['name_am'] ?? null,
            'name_en' => $data['name_en'] ?? null,
            'slug' => $this->buildSlug($data['slug'] ?? '', $template, $data['name_en'] ?? $data['name_am'] ?? $template->name_en ?? ''),
            'orientation' => $data['orientation'] ?? $template->orientation,
            'layout' => $data['layout'] ?? $template->layout,
            'schema' => $this->decodeJson($data['schema'] ?? null),
            'is_active' => filter_var($data['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
        ];
    }

    private function buildSlug(string $value, SignageTemplate $template, string $fallback): string
    {
        $candidate = Str::slug($value !== '' ? $value : $fallback);

        if ($candidate === '') {
            $candidate = (string) Str::uuid();
        }

        $slug = $candidate;
        $counter = 1;

        while ($this->slugExists($slug, $template)) {
            $slug = $candidate . '-' . $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, SignageTemplate $template): bool
    {
        return SignageTemplate::where('slug', $slug)
            ->where('uuid', '!=', $template->uuid)
            ->exists();
    }

    private function decodeJson(?string $value): ?array
    {
        if ($value === null || $value === '') {
            return null;
        }

        return json_decode($value, true);
    }
}
