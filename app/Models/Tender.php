<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Tender extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'description',
        'budget',
        'status',
        'published_at',
        'slug',
        'closing_date',
        'attachment_path',
        'view_count',
        'is_featured',
        'tender_number',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'closing_date' => 'date',
        'view_count' => 'integer',
        'is_featured' => 'boolean',
    ];

    public static function statuses(): array
    {
        return [
            'open',
            'closed',
            'awarded',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Tender $tender) {
            if (empty($tender->slug)) {
                $tender->slug = $tender->buildSlug($tender->title);
            }
        });

        static::saving(function (Tender $tender) {
            if (empty($tender->slug)) {
                $tender->slug = $tender->buildSlug($tender->title);
            }
        });
    }

    protected function buildSlug(?string $value): string
    {
        $base = Str::slug($value ?? '');
        if ($base === '') {
            $base = 'tender';
        }

        $slug = $base;
        $counter = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($this->exists, fn (Builder $q) => $q->where('id', '!=', $this->id))
            ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'open')
            ->where(function (Builder $builder) {
                $builder->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isClosed(): bool
    {
        if (in_array($this->status, ['closed', 'awarded'], true)) {
            return true;
        }

        if ($this->closing_date && $this->closing_date->isPast()) {
            return true;
        }

        return false;
    }

    public function isPublishedForPublic(): bool
    {
        return $this->status === 'open'
            && ($this->published_at === null || $this->published_at->lte(now()));
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        return static::where($field, $value)
            ->first()
            ?? static::where('id', $value)->first();
    }
}
