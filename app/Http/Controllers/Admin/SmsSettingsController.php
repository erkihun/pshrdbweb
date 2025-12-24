<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SmsSettingsController extends Controller
{
    public function edit()
    {
        $settings = $this->getSettings();

        return view('admin.sms-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'enabled' => ['sometimes', 'boolean'],
            'template_ticket_created' => ['required', 'string', 'max:500'],
            'template_ticket_replied' => ['required', 'string', 'max:500'],
            'template_service_request_status' => ['required', 'string', 'max:500'],
            'template_appointment_booked' => ['required', 'string', 'max:500'],
            'template_appointment_status' => ['required', 'string', 'max:500'],
            'template_appointment_reminder' => ['required', 'string', 'max:500'],
        ]);

        $settings = $this->getSettings();
        $settings['enabled'] = (bool) ($data['enabled'] ?? false);
        $settings['template_ticket_created'] = $data['template_ticket_created'];
        $settings['template_ticket_replied'] = $data['template_ticket_replied'];
        $settings['template_service_request_status'] = $data['template_service_request_status'];
        $settings['template_appointment_booked'] = $data['template_appointment_booked'];
        $settings['template_appointment_status'] = $data['template_appointment_status'];
        $settings['template_appointment_reminder'] = $data['template_appointment_reminder'];

        Setting::updateOrCreate(['key' => 'sms.settings'], ['value' => $settings]);
        cache()->forget('sms:settings');

        return back()->with('success', __('common.messages.settings_saved'));
    }

    private function getSettings(): array
    {
        $setting = Setting::where('key', 'sms.settings')->first();
        $defaults = [
            'enabled' => config('services.sms.enabled'),
            'template_ticket_created' => config('services.sms.templates.ticket_created'),
            'template_ticket_replied' => config('services.sms.templates.ticket_replied'),
            'template_service_request_status' => config('services.sms.templates.service_request_status'),
            'template_appointment_booked' => config('services.sms.templates.appointment_booked'),
            'template_appointment_status' => config('services.sms.templates.appointment_status'),
            'template_appointment_reminder' => config('services.sms.templates.appointment_reminder'),
        ];

        return array_merge($defaults, $setting?->value ?? []);
    }
}
