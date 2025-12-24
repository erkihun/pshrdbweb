<x-mail::message>
# Appointment Update

This is an update about your appointment (Reference: **{{ $appointment->reference_code }}**).

<x-mail::panel>
Status: **{{ __('common.status.' . $appointment->status) }}**

{{ $appointment->service->display_name }}

{{ $appointment->slot->starts_at->format('F j, Y') }} at {{ $appointment->slot->starts_at->format('g:i A') }}
</x-mail::panel>

@if($appointment->notes)
> {{ $appointment->notes }}
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
