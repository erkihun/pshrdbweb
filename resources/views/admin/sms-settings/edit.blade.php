@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">SMS Settings</h1>
                <p class="text-sm text-slate-500">Manage SMS templates and toggle.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.sms-settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-3">
                    <input type="hidden" name="enabled" value="0">
                    <input id="enabled" name="enabled" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-slate-900" @checked(old('enabled', $settings['enabled']))>
                    <label for="enabled" class="text-sm font-semibold text-slate-700">Enable SMS</label>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="template_ticket_created">Ticket Created Template</label>
                    <textarea id="template_ticket_created" name="template_ticket_created" rows="2" class="form-textarea">{{ old('template_ticket_created', $settings['template_ticket_created']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Placeholders: {reference_code}, {subject}</p>
                    @error('template_ticket_created')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="template_ticket_replied">Ticket Reply Template</label>
                    <textarea id="template_ticket_replied" name="template_ticket_replied" rows="2" class="form-textarea">{{ old('template_ticket_replied', $settings['template_ticket_replied']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Placeholders: {reference_code}, {status}</p>
                    @error('template_ticket_replied')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="template_service_request_status">Service Request Status Template</label>
                    <textarea id="template_service_request_status" name="template_service_request_status" rows="2" class="form-textarea">{{ old('template_service_request_status', $settings['template_service_request_status']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Placeholders: {reference_code}, {status}</p>
                    @error('template_service_request_status')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="template_appointment_booked">Appointment Confirmation Template</label>
                    <textarea id="template_appointment_booked" name="template_appointment_booked" rows="2" class="form-textarea">{{ old('template_appointment_booked', $settings['template_appointment_booked']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Placeholders: {reference_code}, {service}, {date}, {time}</p>
                    @error('template_appointment_booked')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="template_appointment_status">Appointment Status Template</label>
                    <textarea id="template_appointment_status" name="template_appointment_status" rows="2" class="form-textarea">{{ old('template_appointment_status', $settings['template_appointment_status']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Placeholders: {reference_code}, {status}, {service}</p>
                    @error('template_appointment_status')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="template_appointment_reminder">Appointment Reminder Template</label>
                    <textarea id="template_appointment_reminder" name="template_appointment_reminder" rows="2" class="form-textarea">{{ old('template_appointment_reminder', $settings['template_appointment_reminder']) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Placeholders: {reference_code}, {service}, {date}, {time}</p>
                    @error('template_appointment_reminder')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

