@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white shadow-lg">
                <div>
                    <h1 class="text-2xl font-bold">{{ __('partners.show.heading') }}</h1>
                    <p class="text-sm text-slate-200">{{ $partner->display_name }}</p>
                </div>
            <a href="{{ route('admin.partners.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                {{ __('partners.actions.back') }}
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('partners.show.fields.type') }}</p>
                    <p class="text-lg font-semibold text-slate-900">{{ __('partners.types.' . $partner->type) }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('partners.show.fields.status') }}</p>
                    <p class="text-lg font-semibold text-slate-900">{{ __('partners.statuses.' . ($partner->is_active ? 'active' : 'inactive')) }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs uppercase  text-slate-500">{{ __('partners.show.fields.contact') }}</p>
                <div class="mt-2 text-sm text-slate-700 space-y-1">
                    @if($partner->phone)
                        <div>{{ __('partners.show.contact.phone') }}: {{ $partner->phone }}</div>
                    @endif
                    @if($partner->email)
                        <div>{{ __('partners.show.contact.email') }}: {{ $partner->email }}</div>
                    @endif
                    @if($partner->website_url)
                        <div>{{ __('partners.show.contact.website') }}: <a href="{{ $partner->website_url }}" target="_blank" rel="nofollow noopener" class="text-blue-600 hover:underline">{{ $partner->website_url }}</a></div>
                    @endif
                </div>
            </div>

            <div>
                <p class="text-xs uppercase  text-slate-500">{{ __('partners.show.fields.address') }}</p>
                <p class="mt-2 text-sm text-slate-700">
                    {{ $partner->address ?? '—' }}<br>
                    {{ $partner->city ?? '—' }}, {{ $partner->country ?? '—' }}
                </p>
            </div>

            @if($partner->logo_path)
                <div>
                    <p class="text-xs uppercase  text-slate-500">{{ __('partners.show.fields.logo') }}</p>
                    <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="{{ $partner->name_am }}" class="mt-3 h-24 max-w-full object-contain">
                </div>
            @endif
        </div>
    </div>
@endsection
