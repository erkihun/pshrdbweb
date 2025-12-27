<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrgStatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dimension' => ['required', 'string', 'max:100'],
            'segment' => ['required', 'string', 'max:120'],
            'male' => ['required', 'integer', 'min:0'],
            'female' => ['required', 'integer', 'min:0'],
            'other' => ['required', 'integer', 'min:0'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'month' => ['nullable', 'integer', 'between:1,12'],
        ];
    }
}
