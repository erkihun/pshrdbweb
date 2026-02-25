<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage document requests');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:submitted,under_review,ready,rejected,delivered'],
            'admin_note' => ['nullable', 'string'],
        ];
    }
}
