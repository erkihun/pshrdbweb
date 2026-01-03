@php
    $downloadsTitle = __('common.nav.downloads');
    $seoMeta = [
        'title' => $downloadsTitle,
        'description' => 'Official downloads, documents, and forms for Addis Ababa public service.',
        'url' => route('downloads.index'),
        'canonical' => route('downloads.index'),
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.nav.downloads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8">
                <form class="flex flex-wrap gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex-1 min-w-[220px]">
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-400" for="q">{{ __('common.actions.search') }}</label>
                        <input
                            id="q"
                            name="q"
                            type="text"
                            value="{{ request('q') }}"
                            placeholder="Search by title"
                            class="mt-2 w-full rounded-lg border border-gray-200 px-4 py-2 text-sm focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        >
                    </div>
                    <div class="flex-1 min-w-[220px]">
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-400" for="category">{{ __('common.labels.category') }}</label>
                        <select
                            id="category"
                            name="category"
                            class="mt-2 w-full rounded-lg border border-gray-200 px-4 py-2 text-sm focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        >
                            <option value="">{{ __('common.labels.all') }}</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected(optional($selectedCategory)->id === $category->id)>
                                {{ $category->display_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800"
                        >
                            Filter
                        </button>
                    </div>
                </form>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($documents as $document)
                        @php
                            $fileType = strtolower($document->file_type ?? '');
                        @endphp
                        <a
                            href="{{ route('downloads.show', $document->slug) }}"
                            class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-gray-300"
                        >
                            <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-wide text-blue-600">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full border border-blue-200 bg-blue-50 text-blue-600">
                                    @if ($fileType === 'pdf')
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 2a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V7l-5-5H3zm10 0l5 5h-5V2z" />
                                            <path fill="#fff" d="M9 8h3v6H9z" opacity="0.6"/>
                                        </svg>
                                    @else
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h5l3 3v9H7z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3h6l4 4v3" />
                                        </svg>
                                    @endif
                                </span>
                                <span>
                                    {{ $document->category?->display_name ?? __('common.labels.category') }}
                                </span>
                            </div>
                            <h3 class="mt-3 text-lg font-semibold text-gray-900">{{ $document->display_title }}</h3>
                            @if ($document->display_description)
                                <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                    {{ $document->display_description }}
                                </p>
                            @endif
                            <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                                <div class="inline-flex items-center gap-1">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                                    </svg>
                                    <span class="font-semibold text-gray-900">Views: {{ number_format($document->views_count ?? 0) }}</span>
                                </div>
                                <div class="inline-flex items-center gap-1">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5 5 5-5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9V3" />
                                    </svg>
                                    <span class="font-semibold text-gray-900">Downloads: {{ number_format($document->downloads_count ?? 0) }}</span>
                                </div>
                            </div>
                            <div class="mt-auto pt-4 text-xs font-semibold uppercase tracking-wide text-gray-400">
                                {{ strtoupper($document->file_type) }} · {{ number_format($document->file_size / 1024, 1) }} KB
                            </div>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500 md:col-span-2 lg:col-span-3">
                            {{ __('common.messages.no_documents') }}
                        </div>
                    @endforelse
                </div>

                <div>
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
