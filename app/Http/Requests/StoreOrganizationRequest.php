<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->sanitizeContactFields();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name'],
            'code' => ['nullable', 'string', 'max:255', 'unique:organizations,code'],
            'is_active' => ['sometimes', 'boolean'],
            ...$this->contactRules(),
        ];
    }

    protected function contactRules(): array
    {
        return [
            'phone_primary' => ['nullable', 'string', 'max:50'],
            'phone_secondary' => ['nullable', 'string', 'max:50'],
            'email_primary' => ['nullable', 'email:rfc', 'max:255'],
            'email_secondary' => ['nullable', 'email:rfc', 'max:255'],
            'physical_address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'map_embed_url' => ['nullable', 'url', 'max:2000'],
            'website_url' => ['nullable', 'url', 'max:255'],
        ];
    }

    protected function sanitizeContactFields(): void
    {
        $fields = [
            'phone_primary',
            'phone_secondary',
            'email_primary',
            'email_secondary',
            'physical_address',
            'city',
            'region',
            'country',
            'map_embed_url',
            'website_url',
        ];

        $sanitized = [];
        foreach ($fields as $field) {
            $value = $this->input($field);
            if ($value === null) {
                continue;
            }
            $sanitized[$field] = strip_tags($value);
        }

        $this->merge($sanitized);
    }
}
