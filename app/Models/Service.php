<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
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
        'description',
        'description_am',
        'description_en',
        'requirements',
        'requirements_am',
        'requirements_en',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function feedback()
    {
        return $this->hasMany(ServiceFeedback::class);
    }

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->title_am ?: $this->title_en ?: $this->title)
            : ($this->title_en ?: $this->title_am ?: $this->title);
    }

    public function getDisplayDescriptionAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->description_am ?: $this->description_en ?: $this->description)
            : ($this->description_en ?: $this->description_am ?: $this->description);
    }

    public function getDisplayRequirementsAttribute(): ?string
    {
        return app()->getLocale() === 'am'
            ? ($this->requirements_am ?: $this->requirements_en ?: $this->requirements)
            : ($this->requirements_en ?: $this->requirements_am ?: $this->requirements);
    }
}
