<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrgStatSnapshot extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'org_stat_snapshots';

    protected $fillable = [
        'organization_id',
        'period_type',
        'year',
        'quarter',
        'month',
        'totals',
        'breakdown',
        'created_by',
    ];

    protected $casts = [
        'totals' => 'array',
        'breakdown' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function getPeriodLabelAttribute(): string
    {
        $parts = [$this->period_type];

        if ($this->year) {
            $parts[] = $this->year;
        }

        if ($this->period_type === 'monthly' && $this->month) {
            $parts[] = __('Month: %s', DateTime::createFromFormat('!m', (string) $this->month)->format('F'));
        }

        if ($this->period_type === 'quarterly' && $this->quarter) {
            $parts[] = __('Quarter %s', $this->quarter);
        }

        return implode(' - ', $parts);
    }
}
