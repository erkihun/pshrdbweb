<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMouRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $reference = $this->input('reference_no');
        if ($reference !== null) {
            $this->merge(['reference_no' => strip_tags($reference)]);
        }

        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
    }

    public function rules(): array
    {
        return [
            'partner_id' => ['required', 'exists:partners,id'],
            'title_am' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'signed_at' => ['nullable', 'date'],
            'effective_from' => ['nullable', 'date'],
            'effective_to' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['draft', 'active', 'expired', 'terminated'])],
            'scope_am' => ['nullable', 'string'],
            'scope_en' => ['nullable', 'string'],
            'key_areas_am' => ['nullable', 'string'],
            'key_areas_en' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }
}
