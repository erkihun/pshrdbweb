<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'appointment_service_id',
        'starts_at',
        'ends_at',
        'capacity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(AppointmentService::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getAvailableSeatsAttribute(): int
    {
        if (isset($this->booked_count)) {
            return max(0, $this->capacity - $this->booked_count);
        }

        return max(0, $this->capacity - $this->appointments()->whereIn('status', ['booked', 'confirmed'])->count());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFuture($query)
    {
        return $query->where('starts_at', '>=', now());
    }
}
