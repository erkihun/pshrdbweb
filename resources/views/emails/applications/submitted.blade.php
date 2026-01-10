@component('mail::message')
@if(! empty($logoUrl))
<div style="text-align:center;margin-bottom:16px;">
    <img src="{{ $logoUrl }}" alt="{{ $appName }}" style="max-height:64px;">
</div>
@endif

# Application received

Dear applicant,

We have received your application for **{{ $vacancy?->title ?? 'Vacancy' }}**. Your reference code is **{{ $application->reference_code }}**.

Submitted date: **{{ optional($submittedAt)->format('M d, Y') }}**

@component('mail::button', ['url' => $dashboardUrl, 'color' => 'primary'])
View your applicant dashboard
@endcomponent

If you have questions, please contact the public service office. Keep your reference code for future tracking.

Thank you,<br>
{{ $appName }}
@endcomponent
