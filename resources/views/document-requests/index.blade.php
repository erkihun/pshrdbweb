<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.labels.document_requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($types->isEmpty())
                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    {{ __('common.messages.no_document_request_types') }}
                </div>
            @endif

            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($types as $type)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="text-sm font-semibold text-gray-900">{{ $type->displayName() }}</div>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ app()->getLocale() === 'am' ? $type->requirements_am : $type->requirements_en }}
                        </p>
                        <div class="mt-4 flex items-center justify-between text-sm font-medium">
                            <span class="text-gray-500">{{ __('common.messages.available') }}</span>
                            <a href="{{ route('document-requests.show', $type->slug) }}" class="text-blue-600 hover:text-blue-800">{{ __('common.actions.apply') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
