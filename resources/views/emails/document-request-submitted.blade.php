@component('mail::message')
# {{ __('common.labels.document_request') }}

{{ __('common.messages.document_request_received', ['reference' => $request->reference_code]) }}

**{{ __('common.labels.full_name') }}:** {{ $request->full_name }}
**{{ __('common.labels.phone') }}:** {{ $request->phone }}
@if($request->email)
**{{ __('common.labels.email') }}:** {{ $request->email }}
@endif
**{{ __('common.labels.request_type') }}:** {{ $request->display_type }}

@component('mail::panel')
{{ $request->purpose }}
@endcomponent

{{ __('common.messages.we_will_update_you') }}

{{ config('app.name') }}
@endcomponent
