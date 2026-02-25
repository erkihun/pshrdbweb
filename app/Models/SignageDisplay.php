<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignageDisplay extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'signage_template_id',
        'title_am',
        'title_en',
        'slug',
        'payload',
        'refresh_seconds',
        'is_published',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'refresh_seconds' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(SignageTemplate::class, 'signage_template_id', 'uuid');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
