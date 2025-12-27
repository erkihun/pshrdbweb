<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

final class OfficialMessage extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'name_am',
        'name_en',
        'title',
        'title_am',
        'title_en',
        'message',
        'message_am',
        'message_en',
        'photo_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function localized(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        $localizedKey = "{$field}_{$locale}";

        return $this->{$localizedKey} ?? $this->{$field} ?? null;
    }

    public function newUniqueId(): string
    {
        return (string) Str::uuid7();
    }
}
