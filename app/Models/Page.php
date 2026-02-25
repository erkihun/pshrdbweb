<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'title_am',
        'title_en',
        'body_am',
        'body_en',
        'cover_image_path',
        'is_published',
        'updated_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'am' ? $this->title_am : $this->title_en;
    }

    public function getDisplayBodyAttribute(): string
    {
        return app()->getLocale() === 'am' ? $this->body_am : $this->body_en;
    }
}
