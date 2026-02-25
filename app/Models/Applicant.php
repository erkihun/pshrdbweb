<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Applicant extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'uuid',
        'full_name',
        'date_of_birth',
        'gender',
        'nationality',
        'disability_status',
        'education_level',
        'field_of_study',
        'university_name',
        'graduation_year',
        'gpa',
        'education_document_path',
        'profile_photo_path',
        'address',
        'phone',
        'national_id_number',
        'email',
        'password',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'disability_status' => 'boolean',
        'graduation_year' => 'integer',
        'gpa' => 'decimal:2',
        'last_login_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $applicant): void {
            if (! $applicant->uuid) {
                $applicant->uuid = (string) Str::uuid();
            }
        });
    }

    public function applications()
    {
        return $this->hasMany(VacancyApplication::class);
    }

    public function getNameAttribute(): string
    {
        return $this->full_name;
    }
}
