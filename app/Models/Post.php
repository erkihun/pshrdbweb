<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Contracts\HomeController;
class Post extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'title_am',
        'title_en',
        'slug',
        'body',
        'body_am',
        'body_en',
        'excerpt',
        'excerpt_am',
        'excerpt_en',
        'type',
        'seo_title',
        'seo_description',
        'cover_image_path',
        'is_published',
        'published_at',
        'author_name',
        'posted_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'posted_at' => 'datetime',
    ];

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->title_am ?: $this->title_en ?: $this->title)
            : ($this->title_en ?: $this->title_am ?: $this->title);
    }

    public function getDisplayExcerptAttribute(): ?string
    {
        return app()->getLocale() === 'am'
            ? ($this->excerpt_am ?: $this->excerpt_en ?: $this->excerpt)
            : ($this->excerpt_en ?: $this->excerpt_am ?: $this->excerpt);
    }

    public function getDisplayBodyAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->body_am ?: $this->body_en ?: $this->body)
            : ($this->body_en ?: $this->body_am ?: $this->body);
    }
    protected static function booted()
    {
        static::saved(function () {
            HomeController::clearHomepageCache();
        });

        static::deleted(function () {
            HomeController::clearHomepageCache();
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }
}
