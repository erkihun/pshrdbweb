<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organizationId = $this->route('organization')->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('organizations', 'name')->ignore($organizationId)],
            'code' => ['nullable', 'string', 'max:255', Rule::unique('organizations', 'code')->ignore($organizationId)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
