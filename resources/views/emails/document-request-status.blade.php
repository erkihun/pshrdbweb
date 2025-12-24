@component('mail::message')
# {{ __('common.labels.document_request_status') }}

{{ __('common.messages.document_request_status_changed', ['reference' => $request->reference_code, 'status' => ucfirst($request->status)]) }}

@if($request->admin_note)
**{{ __('common.labels.admin_note') }}:** {{ $request->admin_note }}
@endif

{{ __('common.messages.thank_you') }}

{{ config('app.name') }}
@endcomponent
