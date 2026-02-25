<?php

namespace App\Http\Requests;

use App\Models\CharterService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class CharterServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->commonRules();
    }

    protected function commonRules(): array
    {
        return [
            'department_id' => ['required', 'uuid', 'exists:departments,id'],
            'name_am' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'prerequisites_am' => ['nullable', 'string'],
            'prerequisites_en' => ['nullable', 'string'],
            'service_place_am' => ['nullable', 'string', 'max:255'],
            'service_place_en' => ['nullable', 'string', 'max:255'],
            'working_days' => ['nullable', 'array'],
            'working_days.*' => ['string', Rule::in($this->allowedWorkingDays())],
            'working_hours_start' => ['nullable', 'date_format:H:i'],
            'working_hours_end' => ['nullable', 'date_format:H:i'],
            'break_time_start' => ['nullable', 'date_format:H:i'],
            'break_time_end' => ['nullable', 'date_format:H:i'],
            'service_delivery_mode' => ['nullable', Rule::in(CharterService::DELIVERY_MODES)],
            'fee_amount' => ['nullable', 'numeric', 'min:0'],
            'fee_currency' => ['nullable', 'string', 'max:10'],
            'other_info_am' => ['nullable', 'string'],
            'other_info_en' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function allowedWorkingDays(): array
    {
        return [
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat',
            'Sun',
        ];
    }

    protected function prepareForValidation(): void
    {
        $plainFields = [
            'service_place_am',
            'service_place_en',
            'fee_currency',
        ];

        $sanitized = [];

        foreach ($plainFields as $field) {
            if ($this->filled($field)) {
                $sanitized[$field] = strip_tags($this->input($field));
            }
        }

        $workingDays = array_values(array_filter(
            $this->input('working_days', []),
            fn ($value) => $value !== null && $value !== ''
        ));

        $sanitized['working_days'] = $workingDays;
        $sanitized['is_active'] = $this->boolean('is_active');

        $this->merge($sanitized);
    }
}
