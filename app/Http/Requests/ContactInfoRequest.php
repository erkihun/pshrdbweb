<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class ContactInfoRequest extends FormRequest
{
    protected function sanitizeInput(): void
    {
        $mapIframe = $this->input('map_iframe_html');
        if ($mapIframe) {
            $mapIframe = preg_replace('/<script\b[^>]*>(.*?)<\\/script>/is', '', $mapIframe);
            if (! str_contains(strtolower($mapIframe), '<iframe')) {
                $mapIframe = null;
            }
        }

        $this->merge([
            'bureau_name' => strip_tags($this->input('bureau_name', '')),
            'physical_address' => strip_tags($this->input('physical_address', '')),
            'city' => strip_tags($this->input('city', '')),
            'region' => strip_tags($this->input('region', '')),
            'country' => strip_tags($this->input('country', '')),
            'postal_code' => strip_tags($this->input('postal_code', '')),
            'phone_primary' => strip_tags($this->input('phone_primary', '')),
            'phone_secondary' => strip_tags($this->input('phone_secondary', '')),
            'email_primary' => strip_tags($this->input('email_primary', '')),
            'email_secondary' => strip_tags($this->input('email_secondary', '')),
            'website_url' => strip_tags($this->input('website_url', '')),
            'facebook_url' => strip_tags($this->input('facebook_url', '')),
            'telegram_url' => strip_tags($this->input('telegram_url', '')),
            'x_url' => strip_tags($this->input('x_url', '')),
            'linkedin_url' => strip_tags($this->input('linkedin_url', '')),
            'map_embed_url' => strip_tags($this->input('map_embed_url', '')),
            'map_iframe_html' => $mapIframe,
        ]);
    }

    public function prepareForValidation(): void
    {
        $this->sanitizeInput();
    }

    protected function contactInfoRules(): array
    {
        return [
            'bureau_name' => 'required|string|max:255',
            'physical_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone_primary' => 'nullable|string|max:30',
            'phone_secondary' => 'nullable|string|max:30',
            'email_primary' => 'nullable|email:rfc',
            'email_secondary' => 'nullable|email:rfc',
            'website_url' => 'nullable|url|max:255',
            'office_hours' => 'nullable|string',
            'map_embed_url' => 'nullable|url|max:2000',
            'map_iframe_html' => 'nullable|string|max:5000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'facebook_url' => 'nullable|url|max:255',
            'telegram_url' => 'nullable|url|max:255',
            'x_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
