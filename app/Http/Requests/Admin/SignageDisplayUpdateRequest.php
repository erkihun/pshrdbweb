<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignageDisplayUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $display = $this->route('signage_display');

        return [
            'signage_template_id' => ['required', 'exists:signage_templates,uuid'],
            'title_am' => ['nullable', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('signage_displays', 'slug')->ignore($display, 'uuid'),
            ],
            'payload' => ['nullable', 'string', 'json'],
            'refresh_seconds' => ['nullable', 'integer', 'min:5', 'max:3600'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
