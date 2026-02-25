<?php

namespace App\Models;

use App\Models\CharterService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name_am',
        'name_en',
        'slug',
        'sort_order',
        'is_active',
        'building_name',
        'floor_number',
        'side',
        'office_room',
        'department_address_note_am',
        'department_address_note_en',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function charterServices(): HasMany
    {
        return $this->hasMany(CharterService::class);
    }

    public function services(): HasMany
    {
        return $this->charterServices();
    }

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'am') {
            return $this->name_am ?? $this->name_en;
        }

        return $this->name_en ?? $this->name_am;
    }

    public function localizedAddressNote(): ?string
    {
        if (app()->getLocale() === 'am') {
            return $this->department_address_note_am ?? $this->department_address_note_en;
        }

        return $this->department_address_note_en ?? $this->department_address_note_am;
    }

    public function hasLocationDetails(): bool
    {
        return filled($this->building_name)
            || filled($this->floor_number)
            || filled($this->side)
            || filled($this->office_room)
            || filled($this->department_address_note_am)
            || filled($this->department_address_note_en);
    }

    public function getSideLabelAttribute(): ?string
    {
        return $this->side ? ucfirst($this->side) : null;
    }
}
