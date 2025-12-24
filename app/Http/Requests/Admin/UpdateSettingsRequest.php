<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage settings') ?? false;
    }

    public function rules(): array
    {
        return [
            'site_name_am' => ['required', 'string', 'max:255'],
            'site_name_en' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'address_am' => ['nullable', 'string', 'max:500'],
            'address_en' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'working_hours_am' => ['nullable', 'string', 'max:255'],
            'working_hours_en' => ['nullable', 'string', 'max:255'],
            'admin_email' => ['nullable', 'email', 'max:255'],
            'enable_email' => ['sometimes', 'boolean'],
            'enable_sms' => ['sometimes', 'boolean'],
            'analytics_enabled' => ['sometimes', 'boolean'],
            'quick_links' => ['nullable', 'array'],
            'quick_links.*.label_am' => ['nullable', 'string', 'max:255'],
            'quick_links.*.label_en' => ['nullable', 'string', 'max:255'],
            'quick_links.*.url' => ['nullable', 'url', 'max:255'],
            'social_links' => ['nullable', 'array'],
            'social_links.*.label_am' => ['nullable', 'string', 'max:255'],
            'social_links.*.label_en' => ['nullable', 'string', 'max:255'],
            'social_links.*.url' => ['nullable', 'url', 'max:255'],
        ];
    }
}

