<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use App\Models\User;

class Mou extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'partner_id',
        'title_am',
        'title_en',
        'reference_no',
        'signed_at',
        'effective_from',
        'effective_to',
        'status',
        'scope_am',
        'scope_en',
        'key_areas_am',
        'key_areas_en',
        'attachment_path',
        'is_published',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'signed_at' => 'date',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_published' => 'boolean',
    ];

    protected $appends = [
        'public_slug',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'am'
            ? ($this->title_am ?: $this->title_en ?? '')
            : ($this->title_en ?: $this->title_am ?? '');
    }

    public function getPublicSlugAttribute(): string
    {
        if ($this->reference_no) {
            return Str::slug($this->reference_no);
        }

        $fallback = $this->title_en ?: $this->title_am ?: optional($this->partner)->display_name ?: 'mou';
        $slug = Str::slug($fallback);

        return $slug ?: ('mou-' . substr($this->id, 0, 8));
    }
}
