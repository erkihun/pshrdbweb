<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class ContactInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'bureau_name',
        'physical_address',
        'city',
        'region',
        'country',
        'postal_code',
        'phone_primary',
        'phone_secondary',
        'email_primary',
        'email_secondary',
        'website_url',
        'office_hours',
        'map_embed_url',
        'map_iframe_html',
        'latitude',
        'longitude',
        'facebook_url',
        'telegram_url',
        'x_url',
        'linkedin_url',
        'is_active',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    protected static function booted(): void
    {
        static::creating(function (ContactInfo $contactInfo) {
            if (empty($contactInfo->uuid)) {
                $contactInfo->uuid = (string) Str::uuid();
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function current(): ?self
    {
        return static::active()->latest('updated_at')->first();
    }

    public function getSanitizedOfficeHoursAttribute(): string
    {
        $value = trim($this->office_hours ?? '');

        if ($value === '') {
            return '';
        }

        return strip_tags($value, $this->allowedOfficeHoursTags());
    }

    protected function allowedOfficeHoursTags(): string
    {
        return '<p><br><strong><em><b><i><u><ul><ol><li><div><span><a><small><h3><h4><h5><h6><blockquote>';
    }
}
