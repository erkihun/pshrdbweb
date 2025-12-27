@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.chat_support') }}</h1>
            <p class="text-sm text-slate-500">{{ __('common.messages.chat_management_hint') }}</p>
        </div>

        <div class="grid gap-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.labels.waiting') }}</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($waiting as $session)
                        <a href="{{ route('admin.chats.show', $session) }}" class="block rounded-xl border border-slate-100 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-300">
                            <div class="flex items-center justify-between text-xs uppercase tracking-wide text-slate-400">
                                <span>{{ $session->visitor_name ?? __('common.labels.anonymous') }}</span>
                                <span>{{ $session->started_at ? ethiopian_date($session->started_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, H:i', true) : '' }}</span>
                            </div>
                            <div class="text-slate-900">{{ $session->visitor_email ?? __('common.labels.phone') }} / {{ $session->visitor_phone ?? __('common.labels.email') }}</div>
                        </a>
                    @empty
                        <div class="text-sm text-slate-500">{{ __('common.messages.no_chats') }}</div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.labels.active') }}</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($active as $session)
                        <a href="{{ route('admin.chats.show', $session) }}" class="block rounded-xl border border-slate-100 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-300">
                            <div class="flex items-center justify-between text-xs uppercase tracking-wide text-slate-400">
                                <span>{{ $session->visitor_name ?? __('common.labels.anonymous') }}</span>
                                <span>{{ $session->started_at ? ethiopian_date($session->started_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, H:i', true) : '' }}</span>
                            </div>
                            <div class="text-slate-900">{{ $session->assignedTo?->name ?? __('common.labels.unassigned') }}</div>
                        </a>
                    @empty
                        <div class="text-sm text-slate-500">{{ __('common.messages.no_chats') }}</div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('common.labels.closed') }}</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($closed as $session)
                        <a href="{{ route('admin.chats.show', $session) }}" class="block rounded-xl border border-slate-100 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-300">
                            <div class="flex items-center justify-between text-xs uppercase tracking-wide text-slate-400">
                                <span>{{ $session->visitor_name ?? __('common.labels.anonymous') }}</span>
                                <span>{{ $session->closed_at ? ethiopian_date($session->closed_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, H:i', true) : '' }}</span>
                            </div>
                            <div class="text-slate-900">{{ $session->assignedTo?->name ?? __('common.labels.unassigned') }}</div>
                        </a>
                    @empty
                        <div class="text-sm text-slate-500">{{ __('common.messages.no_chats') }}</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection

