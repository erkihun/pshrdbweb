<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateHomeSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title_am' => ['nullable', 'string', 'max:255'],
            'subtitle_am' => ['nullable', 'string', 'max:255'],

            'transition_style' => ['required', Rule::in(['wave', 'glide', 'swirl', 'drift', 'pulse'])],
            'content_alignment' => ['required', Rule::in(['left', 'center', 'right'])],
            'image' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
