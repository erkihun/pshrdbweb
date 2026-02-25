<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'document_category_id',
        'title',
        'title_am',
        'title_en',
        'slug',
        'description',
        'description_am',
        'description_en',
        'file_path',
        'file_type',
        'file_size',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'file_size' => 'integer',
    ];

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->title_am ?: $this->title_en ?: $this->title)
            : ($this->title_en ?: $this->title_am ?: $this->title);
    }

    public function getDisplayDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'am'
            ? ($this->description_am ?: $this->description_en ?: $this->description)
            : ($this->description_en ?: $this->description_am ?: $this->description);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }
}
