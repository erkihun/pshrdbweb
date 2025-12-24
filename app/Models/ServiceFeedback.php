<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceFeedback extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'service_id',
        'rating',
        'comment',
        'full_name',
        'phone',
        'email',
        'locale',
        'ip_hash',
        'submitted_at',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'submitted_at' => 'datetime',
        'is_approved' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
