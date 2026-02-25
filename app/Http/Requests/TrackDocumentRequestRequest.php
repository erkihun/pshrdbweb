<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackDocumentRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_code' => ['required', 'string'],
        ];
    }
}
