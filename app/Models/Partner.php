<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Partner extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name_am',
        'name_en',
        'short_name',
        'type',
        'country',
        'city',
        'website_url',
        'phone',
        'email',
        'address',
        'logo_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function mous()
    {
        return $this->hasMany(Mou::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->name_am ?: $this->name_en ?: $this->short_name ?? '')
            : ($this->name_en ?: $this->name_am ?: $this->short_name ?? '');
    }
}
