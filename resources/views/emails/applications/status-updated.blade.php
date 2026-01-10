@component('mail::message')
@if(! empty($logoUrl))
<div style="text-align:center;margin-bottom:16px;">
    <img src="{{ $logoUrl }}" alt="{{ $appName }}" style="max-height:64px;">
</div>
@endif

# Application status updated

Your application for **{{ $vacancy?->title ?? 'Vacancy' }}** has been updated.

Current status: **{{ $statusLabel }}**

@if(! empty($application->admin_note))
Admin note: {{ $application->admin_note }}
@endif

@component('mail::button', ['url' => $dashboardUrl, 'color' => 'primary'])
View application status
@endcomponent

Thank you for your interest in public service opportunities.

Regards,<br>
{{ $appName }}
@endcomponent
