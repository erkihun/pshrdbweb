<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page->display_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                @if ($page->cover_image_path)
                    <img
                        src="{{ asset('storage/' . $page->cover_image_path) }}"
                        alt="{{ $page->display_title }}"
                        class="mb-6 h-64 w-full rounded-2xl object-cover"
                    >
                @endif
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    {{ __('common.labels.last_updated') }}: {{ $page->updated_at?->format('M d, Y') }}
                </p>

                <div class="prose prose-gray max-w-none">
                    {!! nl2br(e($page->display_body)) !!}
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
