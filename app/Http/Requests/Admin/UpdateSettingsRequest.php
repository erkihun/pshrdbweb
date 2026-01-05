<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateSettingsRequest extends FormRequest
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

            // Images
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,ico', 'max:1024'],

            // Contact
            'address_am' => ['nullable', 'string', 'max:500'],
            'address_en' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'working_hours_am' => ['nullable', 'string', 'max:255'],
            'working_hours_en' => ['nullable', 'string', 'max:255'],

            // Notifications
            'admin_email' => ['nullable', 'email', 'max:255'],
            'enable_email' => ['nullable', 'boolean'],
            'enable_sms' => ['nullable', 'boolean'],

            // Analytics
            'analytics_enabled' => ['nullable', 'boolean'],

            'description_am' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:500'],
            'google_verification' => ['nullable', 'string', 'max:255'],
            'bing_verification' => ['nullable', 'string', 'max:255'],

            // Footer links
            'quick_links' => ['nullable', 'array'],
            'quick_links.*.label_am' => ['nullable', 'string', 'max:255'],
            'quick_links.*.label_en' => ['nullable', 'string', 'max:255'],
            'quick_links.*.url' => ['nullable', 'string', 'max:2048'],

            'social_links' => ['nullable', 'array'],
            'social_links.*.label_am' => ['nullable', 'string', 'max:255'],
            'social_links.*.label_en' => ['nullable', 'string', 'max:255'],
            'social_links.*.url' => ['nullable', 'string', 'max:2048'],

            'privacy_title_am' => ['nullable', 'string', 'max:255'],
            'privacy_title_en' => ['nullable', 'string', 'max:255'],
            'privacy_body_am' => ['nullable', 'string'],
            'privacy_body_en' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // When unchecked, checkboxes are not sent => force them to false.
        $this->merge([
            'enable_email' => $this->boolean('enable_email'),
            'enable_sms' => $this->boolean('enable_sms'),
            'analytics_enabled' => $this->boolean('analytics_enabled'),
        ]);
    }
}
