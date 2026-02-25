<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.labels.track_request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <form method="POST" action="{{ route('document-requests.track.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-semibold text-gray-700" for="reference_code">{{ __('common.labels.reference_code') }}</label>
                        <input id="reference_code" name="reference_code" type="text" value="{{ $reference ?? '' }}" class="form-input" required>
                        @error('reference_code')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">{{ __('common.actions.search') }}</button>
                    </div>
                </form>
            </div>

            @if ($documentRequest)
                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm space-y-3">
                    <div class="text-sm text-gray-500">{{ __('common.labels.reference_code') }}: {{ $documentRequest->reference_code }}</div>
                    <div class="text-sm text-gray-900">{{ $documentRequest->full_name }}</div>
                    <div class="text-sm text-gray-500">{{ $documentRequest->phone }} {{ $documentRequest->email ? '| ' . $documentRequest->email : '' }}</div>
                    <div class="text-sm text-gray-500">{{ __('common.labels.status') }}: {{ __('common.status.' . $documentRequest->status) }}</div>
                    @if ($documentRequest->admin_note)
                        <div class="text-sm text-gray-700">{{ __('common.labels.admin_note') }}: {{ $documentRequest->admin_note }}</div>
                    @endif
                </div>
            @elseif (isset($reference))
                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm text-sm text-gray-500">
                    {{ __('common.messages.no_document_request_found') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
