@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ url('/admin/search') }}" class="flex flex-col gap-3 lg:flex-row lg:items-end">
                <div class="flex-1">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('common.actions.search') }}</label>
                    <input
                        name="q"
                        type="text"
                        value="{{ $query }}"
                        placeholder="{{ __('common.labels.search_placeholder') }}"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-slate-800">
                        {{ __('common.actions.search') }}
                    </button>
                </div>
            </form>
        </section>

        @unless ($hasQuery)
            <section class="rounded-2xl border border-dashed border-slate-200 bg-white p-6 text-center text-sm text-slate-500">
                {{ __('common.messages.search_prompt') }}
            </section>
        @else
            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Posts</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $posts?->total() ?? 0 }} results</p>
                        </div>
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">News / Announcements</span>
                    </div>

                    <div class="mt-4 space-y-4">
                        @forelse($posts ?? [] as $post)
                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-slate-900">{{ $post->display_title }}</h3>
                                    <span class="text-[11px] uppercase tracking-wide text-slate-500">{{ $post->type }}</span>
                                </div>
                                <p class="mt-1 text-xs text-slate-500">{{ $post->slug }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No posts matched your search.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Services</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $services?->total() ?? 0 }} results</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ __('common.nav.services') }}</span>
                    </div>

                    <div class="mt-4 space-y-4">
                        @forelse($services ?? [] as $service)
                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                                <h3 class="text-sm font-semibold text-slate-900">{{ $service->display_title }}</h3>
                                <p class="text-xs text-slate-500">{{ $service->slug }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No services found.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Documents</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $documents?->total() ?? 0 }} results</p>
                    </div>

                    <div class="mt-4 space-y-4">
                        @forelse($documents ?? [] as $document)
                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                                <h3 class="text-sm font-semibold text-slate-900">{{ $document->display_title }}</h3>
                                <p class="text-xs text-slate-500">{{ $document->file_type ?? 'Document' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No documents matched.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Pages</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $pages?->total() ?? 0 }} results</p>
                    </div>

                    <div class="mt-4 space-y-4">
                        @forelse($pages ?? [] as $page)
                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                                <h3 class="text-sm font-semibold text-slate-900">{{ $page->display_title ?? $page->key }}</h3>
                                <p class="text-xs text-slate-500">{{ $page->key }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No pages matched.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        @endif
    </div>
@endsection
