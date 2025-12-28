<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class DepartmentRequest extends FormRequest
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
            'name_am' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'building_name' => ['nullable', 'string', 'max:255'],
            'floor_number' => ['nullable', 'string', 'max:50'],
            'side' => ['nullable', Rule::in(['left', 'right', 'center'])],
            'office_room' => ['nullable', 'string', 'max:50'],
            'department_address_note_am' => ['nullable', 'string'],
            'department_address_note_en' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $plainFields = [
            'name_am',
            'name_en',
            'building_name',
            'floor_number',
            'office_room',
        ];

        $sanitized = [];
        foreach ($plainFields as $field) {
            if ($this->filled($field)) {
                $sanitized[$field] = strip_tags($this->input($field));
            }
        }

        if ($this->filled('side')) {
            $sanitized['side'] = strip_tags($this->input('side'));
        }

        $sanitized['sort_order'] = $this->input('sort_order', 0);
        $sanitized['is_active'] = $this->boolean('is_active');

        $this->merge($sanitized);
    }
}
