<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SignageTemplate extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'name_am',
        'name_en',
        'slug',
        'orientation',
        'layout',
        'schema',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'schema' => 'array',
        'is_active' => 'boolean',
    ];

    public function displays(): HasMany
    {
        return $this->hasMany(SignageDisplay::class, 'signage_template_id', 'uuid');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
