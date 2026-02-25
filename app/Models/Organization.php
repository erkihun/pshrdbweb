<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\OrgStatSnapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Organization extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'phone_primary',
        'phone_secondary',
        'email_primary',
        'email_secondary',
        'physical_address',
        'city',
        'region',
        'country',
        'map_embed_url',
        'website_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orgStats()
    {
        return $this->hasMany(OrgStat::class);
    }

    public function snapshots()
    {
        return $this->hasMany(OrgStatSnapshot::class);
    }
}
