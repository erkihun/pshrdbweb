<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $document->display_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    {{ $document->category?->name ?? __('common.labels.category') }}
                </div>
                <h1 class="mt-3 text-2xl font-semibold text-gray-900">{{ $document->display_title }}</h1>
                <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-gray-400">
                    {{ __('common.labels.last_updated') }}: {{ $document->updated_at?->format('M d, Y') }}
                </p>

                @if ($document->display_description)
                    <div class="mt-4 text-sm text-gray-700 whitespace-pre-line">
                        {{ $document->display_description }}
                    </div>
                @endif

                <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                    <div class="flex flex-wrap gap-4">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('common.labels.type') }}</span>
                            <div class="mt-1 font-medium text-gray-800">{{ strtoupper($document->file_type) }}</div>
                        </div>
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Size</span>
                            <div class="mt-1 font-medium text-gray-800">
                                {{ number_format($document->file_size / 1024, 1) }} KB
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-4">
                    <a
                        href="{{ route('downloads.file', $document->slug) }}"
                        class="btn-primary"
                    >
                        {{ __('common.actions.download') }}
                    </a>
                    <a href="{{ route('downloads.index') }}" class="btn-secondary">
                        {{ __('common.actions.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
