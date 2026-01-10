<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vacancy extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'description',
        'location',
        'status',
        'deadline',
        'published_at',
        'is_published',
        'code',
        'notes',
        'slots',
    ];

    protected $casts = [
        'deadline' => 'date',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'slots' => 'integer',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_OPEN,
            self::STATUS_CLOSED,
        ];
    }

    public function getDeadlineBadgeAttribute(): string
    {
        if (! $this->deadline) {
            return 'N/A';
        }

        return $this->deadline->isPast() ? 'Expired' : 'Active';
    }

    public function getDeadlineLabelAttribute(): string
    {
        if (! $this->deadline) {
            return __('common.not_available');
        }

        return $this->deadline->format('M d, Y');
    }

    public function isExpired(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    public function scopeVisibleForPublic($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now())
            ->whereNotNull('deadline')
            ->whereDate('deadline', '>=', today());
    }

    public function scopePublishedForPublic($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function getPublicSlugAttribute(): string
    {
        if ($this->code) {
            return $this->code;
        }

        return $this->id;
    }

    public function publicUrl(): string
    {
        return route('vacancies.show', ['slug' => $this->public_slug]);
    }

    public function displayDeadlineLabel(): string
    {
        return $this->deadline ? $this->deadline->format('M d, Y') : __('common.not_available');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => ucfirst($this->status ?? self::STATUS_DRAFT)
        );
    }
}
