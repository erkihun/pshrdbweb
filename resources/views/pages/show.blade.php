<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold leading-tight text-gray-900 md:text-3xl">
                    {{ $page->display_title }}
                </h1>
                @if($page->updated_at)
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('common.labels.last_updated') }}:
                        <time datetime="{{ $page->updated_at->toIso8601String() }}" class="font-medium text-gray-700">
                            {{ $page->updated_at->translatedFormat('F d, Y') }}
                        </time>
                    </p>
                @endif
            </div>
        </div>
    </x-slot>

    @php
        $pageKeys = ['history', 'about', 'mission_vision_values', 'structure', 'leadership'];
        $pagesByKey = collect($pageKeys)
            ->mapWithKeys(fn ($key) => [
                $key => [
                    'page' => $pages->firstWhere('key', $key),
                    'icon' => match($key) {
                        'history' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'about' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                        'mission_vision_values' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                        'structure' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                        'leadership' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 5.197v-1a6 6 0 00-4.5-5.799M12 11a3 3 0 100-6 3 3 0 000 6z',
                        default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    }
                ]
            ])
            ->filter(fn($item) => !is_null($item['page']));
    @endphp

    {{-- Simplified Navigation --}}
    <div class="sticky top-0 z-20 pt-4 bg-white border-b border-gray-100">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <nav class="pb-2">
                <ul class="flex gap-1 overflow-x-auto py-2">
                    @foreach($pagesByKey as $tabKey => $tabData)
                        @php $isActive = $page->key === $tabKey; @endphp
                        <li>
                            <a
                                href="{{ route('pages.show', ['key' => $tabKey]) }}"
                                class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg px-4 py-2.5 text-sm font-medium transition-colors {{ $isActive ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                                {{ $isActive ? 'aria-current="page"' : '' }}
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tabData['icon'] }}" />
                                </svg>
                                @php
                                    $translationKey = 'pages.sections.' . $tabKey;
                                    $tabLabel = \Illuminate\Support\Facades\Lang::has($translationKey)
                                        ? __($translationKey)
                                        : ucfirst(str_replace('_', ' ', $tabKey));
                                @endphp
                                {{ $tabLabel }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                {{-- Compact Cover Image --}}
           @if ($page->cover_image_path)
    <div class="relative px-6 py-4 sm:px-8 flex justify-center">
        <img
            src="{{ asset('storage/' . ltrim($page->cover_image_path, '/')) }}"
            alt="{{ $page->display_title }}"
            class="w-32 h-20 rounded-xl object-contain shadow-sm"
            loading="lazy"
            onerror="this.style.display='none'"
        />
    </div>
@endif


                <div class="p-6">
                    {{-- Content Area --}}
                    <div class="mt-2">
                        <x-rich-content class="prose-sm">
                            {!! $page->display_body !!}
                        </x-rich-content>
                    </div>

                    {{-- Back Button --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <a
                            href="{{ url()->previous() }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:border-gray-300 hover:bg-gray-50"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            {{ __('common.actions.back') ?? 'Back' }}
                        </a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
