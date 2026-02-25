<x-mail::message>
# Appointment Reminder

This is a friendly reminder about your upcoming appointment.

<x-mail::panel>
{{ $appointment->service->display_name }}

{{ $appointment->slot->starts_at->format('F j, Y') }} at {{ $appointment->slot->starts_at->format('g:i A') }}

Reference Code: **{{ $appointment->reference_code }}**
</x-mail::panel>

We look forward to seeing you soon.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
