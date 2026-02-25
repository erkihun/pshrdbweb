@component('mail::message')
@if(! empty($logoUrl))
<div style="text-align:center;margin-bottom:16px;">
    <img src="{{ $logoUrl }}" alt="{{ $appName }}" style="max-height:64px;">
</div>
@endif

# Application withdrawn

Your application for **{{ $vacancy?->title ?? 'Vacancy' }}** has been withdrawn.

Reference code: **{{ $application->reference_code }}**

@component('mail::button', ['url' => $dashboardUrl, 'color' => 'primary'])
Return to your dashboard
@endcomponent

If this was not intended, please contact the public service office immediately.

Sincerely,<br>
{{ $appName }}
@endcomponent
