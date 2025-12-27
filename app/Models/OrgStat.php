<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrgStat extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'organization_id',
        'dimension',
        'segment',
        'male',
        'female',
        'other',
        'year',
        'month',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getTotalAttribute(): int
    {
        return $this->male + $this->female + $this->other;
    }
}
