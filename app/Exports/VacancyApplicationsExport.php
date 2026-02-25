<?php

namespace App\Exports;

use App\Models\VacancyApplication;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VacancyApplicationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private readonly array $filters = [])
    {
    }

    public function query(): Builder
    {
        $query = VacancyApplication::query()
            ->with('vacancy')
            ->orderByDesc('created_at');

        if (! empty($this->filters['vacancy_id'])) {
            $query->where('vacancy_id', $this->filters['vacancy_id']);
        }

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (! empty($this->filters['from'])) {
            $query->whereDate('created_at', '>=', $this->filters['from']);
        }

        if (! empty($this->filters['to'])) {
            $query->whereDate('created_at', '<=', $this->filters['to']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Reference code',
            'Vacancy title',
            'Full name',
            'Gender',
            'Disability status',
            'Education level',
            'Field of study',
            'University name',
            'Graduation year',
            'GPA',
            'Phone',
            'Email',
            'National ID number',
            'Application status',
            'Submitted date',
            'Last updated date',
        ];
    }

    public function map($application): array
    {
        $vacancyTitle = $application->vacancy?->title ?? '';

        return [
            $application->reference_code,
            $vacancyTitle,
            $application->full_name,
            $application->gender ? ucfirst($application->gender) : '',
            $application->disability_status ? 'Yes' : 'No',
            $application->education_level ?? '',
            $application->field_of_study ?? '',
            $application->university_name ?? '',
            $application->graduation_year ?? '',
            $application->gpa ?? '',
            $application->phone ?? '',
            $application->email ?? '',
            $application->national_id_number ?? '',
            $application->status_label ?? ucfirst((string) $application->status),
            optional($application->created_at)->format('Y-m-d H:i'),
            optional($application->updated_at)->format('Y-m-d H:i'),
        ];
    }
}
