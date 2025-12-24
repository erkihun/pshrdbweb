<x-mail::message>
# Appointment Confirmed

Your appointment is confirmed. Keep the reference code handy for check-in.

<x-mail::panel>
{{ $appointment->service->display_name }}

{{ $appointment->slot->starts_at->format('F j, Y') }} at {{ $appointment->slot->starts_at->format('g:i A') }}

Reference Code: **{{ $appointment->reference_code }}**
</x-mail::panel>

@if($appointment->notes)
> {{ $appointment->notes }}
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
