<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class CharterService extends Model
{
    use HasFactory;

    public const DELIVERY_MODES = [
        'in_person',
        'online',
        'both',
    ];

    protected $fillable = [
        'department_id',
        'name_am',
        'name_en',
        'slug',
        'prerequisites_am',
        'prerequisites_en',
        'service_place_am',
        'service_place_en',
        'working_days',
        'working_hours_start',
        'working_hours_end',
        'break_time_start',
        'break_time_end',
        'service_delivery_mode',
        'fee_amount',
        'fee_currency',
        'other_info_am',
        'other_info_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'working_days' => 'array',
        'is_active' => 'boolean',
        'fee_amount' => 'decimal:2',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function localized(string $field, string $fallback = 'am'): ?string
    {
        $localeField = "{$field}_" . app()->getLocale();
        if (!empty($this->{$localeField})) {
            return $this->{$localeField};
        }

        $fallbackField = "{$field}_{$fallback}";
        if ($fallbackField !== $localeField && !empty($this->{$fallbackField})) {
            return $this->{$fallbackField};
        }

        $genericFallback = "{$field}_am";
        return $this->{$genericFallback} ?? $this->{"{$field}_en"} ?? null;
    }

    public function getDisplayNameAttribute(): ?string
    {
        return $this->localized('name');
    }

    public function getWorkingDaysLabelAttribute(): string
    {
        if (empty($this->working_days)) {
            return '';
        }

        $labels = array_map(
            fn ($day) => self::localizedWorkingDay($day),
            $this->working_days
        );

        return implode(', ', $labels);
    }

    protected static function localizedWorkingDay(string $day): string
    {
        $key = 'common.citizen_charter.working_days.' . strtolower($day);
        if (Lang::has($key)) {
            return Lang::get($key);
        }

        return ucfirst($day);
    }

    public function getWorkingHoursLabelAttribute(): string
    {
        $slots = [];

        if ($this->working_hours_start && $this->working_hours_end) {
            $slots[] = sprintf('%s - %s', $this->working_hours_start, $this->working_hours_end);
        } elseif ($this->working_hours_start || $this->working_hours_end) {
            $slots[] = trim("{$this->working_hours_start} {$this->working_hours_end}");
        }

        if ($this->break_time_start && $this->break_time_end) {
            $slots[] = sprintf('%s - %s break', $this->break_time_start, $this->break_time_end);
        }

        return trim(implode(' · ', $slots));
    }

    public function getDeliveryModeLabelAttribute(): string
    {
        if (! $this->service_delivery_mode) {
            return '';
        }

        return self::deliveryModeLabel($this->service_delivery_mode);
    }

    public static function deliveryModeOptions(): array
    {
        return collect(self::DELIVERY_MODES)
            ->mapWithKeys(fn ($mode) => [$mode => self::deliveryModeLabel($mode)])
            ->toArray();
    }

    protected static function deliveryModeLabel(string $mode): string
    {
        $key = 'common.citizen_charter.delivery_modes.' . $mode;
        if (Lang::has($key)) {
            return __($key);
        }

        return Str::of($mode)->replace('_', ' ')->title();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (self $service) {
            if (empty($service->uuid)) {
                $service->uuid = (string) Str::uuid();
            }
        });
    }
}
