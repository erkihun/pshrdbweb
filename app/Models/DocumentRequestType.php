<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentRequestType extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name_am',
        'name_en',
        'slug',
        'requirements_am',
        'requirements_en',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class)->latest('submitted_at');
    }

    public function displayName(): string
    {
        return app()->getLocale() === 'am' ? $this->name_am : $this->name_en;
    }
}
