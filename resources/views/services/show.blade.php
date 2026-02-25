<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $service->display_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[3fr,9fr] lg:items-start lg:gap-10">
                <aside class="space-y-5 overflow-y-auto max-h-[calc(100vh-8rem)] rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                    <div class="flex items-start justify-between gap-6 border-b border-gray-100 pb-4">
                        <div>
                          
                            <h3 class="text-lg font-semibold">{{ __('home.services.highlight') }}</h3>
                       
                        </div>
                     
                    </div>

                    <div class="space-y-3">
                        @foreach ($services as $item)
                            <a
                                href="{{ route('services.show', $item->slug) }}"
                                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 transition hover:bg-blue-50"
                            >
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white">
                                    <x-heroicon-o-light-bulb class="h-4 w-4" aria-hidden="true" />
                                </span>
                                <span class="flex-1">{{ $item->display_title }}</span>
                            </a>
                        @endforeach
                    </div>
                </aside>

                <section class="space-y-6 lg:min-h-[70vh]">
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                      
                        <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-gray-400">
                            {{ __('common.labels.last_updated') }}: {{ $service->updated_at ? ethiopian_date($service->updated_at, 'dd MMMM yyyy') : '' }}
                        </p>
                        @if ($feedbackCount > 0)
                            <div class="mt-3 flex items-center gap-3 text-sm text-gray-700">
                                <div class="flex items-center gap-1">
                                    <span class="font-semibold">{{ $averageRating }}</span>
                                    <span>/5</span>
                                </div>
                                <span class="text-gray-500">({{ $feedbackCount }} {{ __('common.labels.feedback_count') }})</span>
                            </div>
                        @endif
                        <div class="mt-4">
                            <x-rich-content>
                                {!! $service->display_description !!}
                            </x-rich-content>
                        </div>

                        @if ($service->display_requirements)
                            <div class="mt-6 border-t border-gray-100 pt-6">
                                <h3 class="text-sm font-semibold text-gray-900">{{ __('common.labels.requirements') }}</h3>
                                <div class="mt-2">
                                    <x-rich-content class="text-sm">
                                        {!! $service->display_requirements !!}
                                    </x-rich-content>
                                </div>
                            </div>
                        @endif

                        <div class="mt-8 print-hidden">
                            <a
                                href="{{ route('services.index') }}"
                                class="btn-secondary"
                            >
                                {{ __('common.actions.back') }}
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
