<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Models\Applicant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class VacancyApplication extends Model
{
    use HasFactory;
    use HasUuids;
    use Notifiable;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'vacancy_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'cover_letter',
        'cv_path',
        'cv_name',
        'date_of_birth',
        'gender',
        'disability_status',
        'disability_type',
        'education_level',
        'field_of_study',
        'university_name',
        'graduation_year',
        'gpa',
        'education_document_path',
        'profile_photo_path',
        'address',
        'national_id_number',
        'applicant_id',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'status' => 'string',
        'date_of_birth' => 'date',
        'disability_status' => 'boolean',
        'gpa' => 'decimal:2',
        'applicant_id' => 'integer',
    ];

    protected $attributes = [
        'status' => self::STATUS_SUBMITTED,
    ];

    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_SHORTLISTED = 'shortlisted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_HIRED = 'hired';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public static function statuses(): array
    {
        return [
            self::STATUS_SUBMITTED,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_SHORTLISTED,
            self::STATUS_REJECTED,
            self::STATUS_HIRED,
            self::STATUS_WITHDRAWN,
        ];
    }

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getStatusLabelAttribute(): string
    {
        return __('common.status.' . $this->status) ?: ucfirst(str_replace('_', ' ', $this->status));
    }

    public function routeNotificationForMail(): ?string
    {
        return $this->email;
    }

    public function getReferenceCodeAttribute(): string
    {
        return 'VAC-' . strtoupper($this->id);
    }

    public function getSubmittedAtAttribute(): ?Carbon
    {
        return $this->created_at;
    }

    public static function resolveReferenceCode(string $code): ?self
    {
        $normalized = strtoupper(str_replace('VAC-', '', $code));

        return static::where('id', $normalized)
            ->orWhere('id', $code)
            ->first();
    }
}
