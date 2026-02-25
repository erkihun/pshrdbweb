<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.labels.track_request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <form method="POST" action="{{ route('service-requests.track.submit') }}" class="space-y-4" aria-label="{{ __('common.labels.track_request') }}">
                    @csrf
                    <label class="text-sm font-semibold text-gray-700" for="reference_code">{{ __('common.labels.reference_code') }}</label>
                    <input id="reference_code" name="reference_code" type="text" value="{{ old('reference_code', $reference ?? '') }}" class="form-input" required>
                    @error('reference_code')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">{{ __('common.actions.search') }}</button>
                    </div>
                </form>

                @isset($serviceRequest)
                    @if ($serviceRequest)
                        <div class="mt-8 rounded-xl border border-gray-100 bg-gray-50 p-6">
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $serviceRequest->reference_code }}</div>
                            <h3 class="mt-2 text-lg font-semibold text-gray-900">{{ $serviceRequest->subject }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $serviceRequest->full_name }} @if($serviceRequest->phone) · {{ $serviceRequest->phone }} @endif</p>
                            <div class="mt-3 inline-flex items-center gap-2">
                                <span class="badge badge-muted">{{ __('common.status.' . $serviceRequest->status) }}</span>
                                <span class="text-xs text-gray-500">{{ __('common.labels.last_updated') }}: {{ $serviceRequest->updated_at ? ethiopian_date($serviceRequest->updated_at, 'dd MMMM yyyy h:mm a') : '' }}</span>
                            </div>
                            @if ($serviceRequest->admin_note)
                                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                                    <div class="font-semibold">{{ __('common.labels.admin_note') }}</div>
                                    <p class="mt-2 whitespace-pre-line">{{ $serviceRequest->admin_note }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mt-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            {{ __('common.messages.no_request_found') }}
                        </div>
                    @endif
                @endisset
            </div>
        </div>
    </div>
</x-app-layout>
