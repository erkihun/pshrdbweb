<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;

class Tender extends Model
{
    use HasFactory;
    use HasUuids;

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_AWARDED = 'awarded';

    protected $fillable = [
        'title',
        'description',
        'budget',
        'status',
        'published_at',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_CLOSED,
            self::STATUS_AWARDED,
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return strtoupper($this->status);
    }
}
