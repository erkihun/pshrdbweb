<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_code' => ['required', 'string', 'max:64'],
        ];
    }
}
