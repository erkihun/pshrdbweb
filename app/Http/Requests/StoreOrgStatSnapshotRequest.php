<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrgStatSnapshotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period_type' => ['required', Rule::in(['monthly', 'quarterly', 'yearly'])],
            'year' => [
                Rule::requiredIf(fn () => in_array($this->input('period_type'), ['monthly', 'quarterly'], true)),
                'integer',
                'between:2000,2100',
            ],
            'month' => [
                Rule::requiredIf(fn () => $this->input('period_type') === 'monthly'),
                'nullable',
                'integer',
                'between:1,12',
            ],
            'quarter' => [
                Rule::requiredIf(fn () => $this->input('period_type') === 'quarterly'),
                'nullable',
                'integer',
                'between:1,4',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('year')) {
            $this->merge(['year' => $this->toNullableInt('year')]);
        }

        if ($this->has('month')) {
            $this->merge(['month' => $this->toNullableInt('month')]);
        }

        if ($this->has('quarter')) {
            $this->merge(['quarter' => $this->toNullableInt('quarter')]);
        }
    }

    private function toNullableInt(string $key): ?int
    {
        $value = $this->input($key);

        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
