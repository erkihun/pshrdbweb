<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'reference_code',
        'appointment_service_id',
        'appointment_slot_id',
        'full_name',
        'phone',
        'email',
        'notes',
        'status',
        'booked_at',
        'updated_by',
        'reminder_sent_at',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    public static function booted()
    {
        static::creating(function (self $model) {
            $model->reference_code = $model->reference_code ?? static::generateReferenceCode();
        });
    }

    public static function generateReferenceCode(): string
    {
        do {
            $code = 'APT-' . strtoupper(Str::random(6));
        } while (static::where('reference_code', $code)->exists());

        return $code;
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(AppointmentService::class, 'appointment_service_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(AppointmentSlot::class, 'appointment_slot_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
