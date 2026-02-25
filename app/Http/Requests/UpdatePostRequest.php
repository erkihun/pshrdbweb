<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:news,announcement'],
            'title_am' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'excerpt_am' => ['nullable', 'string', 'max:500'],
            'excerpt_en' => ['nullable', 'string', 'max:500'],
            'body_am' => ['required', 'string'],
            'body_en' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'is_published' => ['sometimes', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'gallery_images' => ['nullable', 'array', 'max:4'],
            'gallery_images.*' => ['image', 'max:4096'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title_am' => trim(strip_tags($this->input('title_am'))),
            'title_en' => trim(strip_tags($this->input('title_en'))),
            'excerpt_am' => trim(strip_tags($this->input('excerpt_am'))),
            'excerpt_en' => trim(strip_tags($this->input('excerpt_en'))),
            'seo_title' => trim(strip_tags($this->input('seo_title'))),
            'seo_description' => trim(strip_tags($this->input('seo_description'))),
        ]);
    }
}
