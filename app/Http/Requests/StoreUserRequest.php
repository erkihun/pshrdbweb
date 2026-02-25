<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage users');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:32'],
            'national_id' => ['nullable', 'digits:16'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('national_id')) {
            return;
        }

        $this->merge([
            'national_id' => preg_replace('/\D/', '', $this->input('national_id')),
        ]);
    }
}
