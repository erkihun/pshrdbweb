<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use App\Http\Controllers\HomeController;

final class HomeSlide extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'sort_order',
        'is_active',
        'title_am',
        'subtitle_am',
        'transition_style',
        'content_alignment',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'sort_order' => 'int',
    ];

    public function newUniqueId(): string
    {
        return (string) Str::uuid7();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }
        protected static function booted()
    {
        static::saved(function () {
            if (class_exists(HomeController::class)) {
                HomeController::clearHomepageCache();
            }
        });

        static::deleted(function () {
            if (class_exists(HomeController::class)) {
                HomeController::clearHomepageCache();
            }
        });
    }
}
