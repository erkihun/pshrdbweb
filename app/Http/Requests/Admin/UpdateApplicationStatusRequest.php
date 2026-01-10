<?php

namespace App\Http\Requests\Admin;

use App\Models\VacancyApplication;
use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:' . implode(',', VacancyApplication::statuses())],
            'admin_note' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('admin_note')) {
            $this->merge([
                'admin_note' => trim($this->input('admin_note')),
            ]);
        }
    }
}
