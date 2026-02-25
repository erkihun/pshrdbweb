<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRequest extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'reference_code',
        'document_request_type_id',
        'full_name',
        'phone',
        'email',
        'id_number',
        'address_am',
        'address_en',
        'purpose',
        'attachment_path',
        'status',
        'admin_note',
        'submitted_at',
        'updated_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(DocumentRequestType::class, 'document_request_type_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getDisplayTypeAttribute(): string
    {
        return $this->type ? $this->type->displayName() : '';
    }

    public function getAddressAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->address_am ?? '')
            : ($this->address_en ?? '');
    }
}
