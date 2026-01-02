@php
    use Illuminate\Support\Str;
@endphp

@php
    $documentDescription = $document->display_description ?? 'Download this official document from Addis Ababa public service.';
    $seoMeta = [
        'title' => $document->display_title,
        'description' => $documentDescription,
        'url' => route('downloads.show', $document->slug),
        'canonical' => route('downloads.show', $document->slug),
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $document->display_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0">
     
            <div class="grid gap-8 lg:grid-cols-[3fr,9fr]">
                <aside class="space-y-5">
                    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="text-sm font-semibold uppercase  text-slate-500">
                            {{ __('common.labels.other_documents') }}
                        </div>
                        <div class="mt-4 max-h-[720px] overflow-y-auto pr-1 space-y-3">
                            @forelse($otherDocuments as $other)
                                <a
                                    href="{{ route('downloads.show', $other->slug) }}"
                                    class="group block rounded-2xl border border-transparent bg-slate-50 px-4 py-4 text-sm text-slate-700 transition hover:border-blue-200 hover:bg-white hover:shadow-sm"
                                >
                                    @if($other->published_at && $other->published_at->gt(now()->subDays(7)))
                                        <span class="inline-flex items-center justify-center rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-emerald-600">
                                            {{ __('common.labels.new') ?? 'New' }}
                                        </span>
                                    @endif
                                    <div class="mt-2 flex items-start justify-between gap-3">
                                        <div class="text-sm font-semibold text-slate-900 group-hover:text-slate-800">
                                            {{ $other->display_title }}
                                        </div>
                                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ strtoupper($other->file_type) }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500 line-clamp-2">{{ $other->category?->display_name }}</p>
                                    @if($other->display_description)
                                        <p class="mt-3 text-xs text-slate-500 line-clamp-3">{{ Str::limit($other->display_description, 80) }}</p>
                                    @endif
                                    <p class="mt-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                        {{ __('common.labels.last_updated') }} {{ $other->updated_at ? ethiopian_date($other->updated_at, 'dd MMMM yyyy') : '' }}
                                    </p>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-gray-200 px-4 py-6 text-center text-sm text-gray-500">
                                    {{ __('common.messages.no_documents') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </aside>
                <section class="space-y-6">
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                        <div class="px-6 py-4 text-sm font-semibold text-gray-500">
                            {{ __('common.labels.preview') }}
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $document->display_title }}</h2>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('downloads.file', $document->slug) }}" class="btn-primary">
                                        {{ __('common.actions.download') }}
                                    </a>
                                    <a href="{{ route('downloads.index') }}" class="btn-secondary">
                                        {{ __('common.actions.back') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if ($document->file_type === 'pdf' && $document->file_path)
                            <div class="min-h-[520px] overflow-hidden rounded-b-2xl transition-all duration-200 hover:shadow-2xl hover:border-blue-200">
                                <iframe
                                    src="{{ asset('storage/' . $document->file_path) }}"
                                    class="h-[520px] w-full border-0"
                                    aria-label="{{ __('common.labels.preview') }} {{ $document->display_title }}"
                                    title="{{ $document->display_title }} PDF preview"
                                ></iframe>
                            </div>
                        @else
                            <div class="flex h-[520px] items-center justify-center rounded-b-2xl bg-gray-100 text-sm text-gray-500">
                                {{ __('common.messages.no_preview') }}
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
