<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StayConnected extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_am',
        'title_en',
        'url',
        'embed_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->title_am ?: $this->title_en ?: 'Stay connected')
            : ($this->title_en ?: $this->title_am ?: 'Stay connected');
    }
}
