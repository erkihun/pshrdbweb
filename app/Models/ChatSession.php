<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'visitor_name',
        'visitor_phone',
        'visitor_email',
        'status',
        'assigned_to',
        'started_at',
        'closed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('sent_at');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function markActive(): void
    {
        if ($this->status !== 'active') {
            $this->status = 'active';
            $this->save();
        }
    }

    public function markClosed(): void
    {
        $this->status = 'closed';
        $this->closed_at = now();
        $this->save();
    }
}
