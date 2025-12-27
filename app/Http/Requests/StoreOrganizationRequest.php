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

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name'],
            'code' => ['nullable', 'string', 'max:255', 'unique:organizations,code'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
