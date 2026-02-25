<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name_am',
        'name_en',
        'slug',
        'description_am',
        'description_en',
        'duration_minutes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function slots()
    {
        return $this->hasMany(AppointmentSlot::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->name_am ?: $this->name_en)
            : ($this->name_en ?: $this->name_am);
    }

    public function getDisplayDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'am'
            ? ($this->description_am ?: $this->description_en)
            : ($this->description_en ?: $this->description_am);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
