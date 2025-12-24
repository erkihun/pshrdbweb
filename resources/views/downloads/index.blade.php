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
                                    {{ $category->name }}
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
                        <a
                            href="{{ route('downloads.show', $document->slug) }}"
                            class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-gray-300"
                        >
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                {{ $document->category?->name ?? __('common.labels.category') }}
                            </div>
                            <h3 class="mt-3 text-lg font-semibold text-gray-900">{{ $document->display_title }}</h3>
                            @if ($document->display_description)
                                <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                    {{ $document->display_description }}
                                </p>
                            @endif
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
