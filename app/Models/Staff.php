<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'department_id',
        'full_name_am',
        'full_name_en',
        'title_am',
        'title_en',
        'bio_am',
        'bio_en',
        'photo_path',
        'phone',
        'email',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'am' ? $this->full_name_am : $this->full_name_en;
    }

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'am' ? $this->title_am : $this->title_en;
    }

    public function getDisplayBioAttribute(): ?string
    {
        return app()->getLocale() === 'am' ? $this->bio_am : $this->bio_en;
    }
}
