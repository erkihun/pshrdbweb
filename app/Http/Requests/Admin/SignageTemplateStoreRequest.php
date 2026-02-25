<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignageTemplateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_am' => ['nullable', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'orientation' => ['required', Rule::in(['portrait', 'landscape'])],
            'layout' => [
                'required',
                Rule::in(['header_two_cols_footer', 'header_body_footer', 'split_three_rows']),
            ],
            'schema' => ['nullable', 'string', 'json'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
