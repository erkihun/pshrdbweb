@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white shadow-lg">
                <div>
                    <h1 class="text-2xl font-bold">{{ __('mous.show.heading') }}</h1>
                    <p class="text-sm text-slate-200">{{ $mou->title }}</p>
                </div>
            <a href="{{ route('admin.mous.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                {{ __('mous.actions.back') }}
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.partner') }}</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $mou->partner->display_name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.status') }}</p>
                    <p class="text-lg font-semibold text-slate-900">{{ __('mous.statuses.' . $mou->status) }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.signed') }}</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $mou->signed_at?->toDateString() ?? '—' }}</p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.reference') }}</p>
                    <p class="text-sm text-slate-700">{{ $mou->reference_no ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.effective_period') }}</p>
                    <p class="text-sm text-slate-700">
                        {{ $mou->effective_from?->toDateString() ?? '—' }} – {{ $mou->effective_to?->toDateString() ?? '—' }}
                    </p>
                </div>
            </div>

            <div>
                <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.scope_am') }}</p>
                <div class="prose max-w-none text-sm text-slate-700 border border-slate-100 rounded-lg p-4">{!! $mou->scope_am ?? '—' !!}</div>
            </div>

            <div>
                <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.scope_en') }}</p>
                <div class="prose max-w-none text-sm text-slate-700 border border-slate-100 rounded-lg p-4">{!! $mou->scope_en ?? '—' !!}</div>
            </div>

            <div>
                <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.key_areas_am') }}</p>
                <div class="prose max-w-none text-sm text-slate-700 border border-slate-100 rounded-lg p-4">{!! $mou->key_areas_am ?? '—' !!}</div>
            </div>

            <div>
                <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.key_areas_en') }}</p>
                <div class="prose max-w-none text-sm text-slate-700 border border-slate-100 rounded-lg p-4">{!! $mou->key_areas_en ?? '—' !!}</div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.attachment') }}</p>
                    @if($mou->attachment_path)
                        <a href="{{ asset('storage/' . $mou->attachment_path) }}" target="_blank" rel="nofollow noopener" class="text-blue-600 hover:underline text-sm font-semibold">
                            {{ __('mous.show.fields.download') }}
                        </a>
                    @else
                        <p class="text-sm text-slate-500">{{ __('mous.show.fields.no_attachment') }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('mous.show.fields.published') }}</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $mou->is_published ? __('common.labels.yes') : __('common.labels.no') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
