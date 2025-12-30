@extends('admin.layouts.app')

@section('content')
    @php
        $statusOptions = [
            'draft' => __('mous.statuses.draft'),
            'active' => __('mous.statuses.active'),
            'expired' => __('mous.statuses.expired'),
            'terminated' => __('mous.statuses.terminated'),
        ];
    @endphp
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white shadow-lg">
            <div>
                <h1 class="text-2xl font-bold">{{ __('mous.index.title') }}</h1>
                <p class="text-sm text-slate-200">{{ __('mous.index.description') }}</p>
            </div>
            <a href="{{ route('admin.mous.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                {{ __('common.actions.create') }}
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form class="grid gap-4 lg:grid-cols-5">
                <div>
                    <label class="text-sm font-semibold text-slate-700">{{ __('mous.index.filters.partner') }}</label>
                    <select name="partner" class="form-input">
                        <option value="">{{ __('mous.index.filters.all_partners') }}</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" @selected(request('partner') === $partner->id)>{{ $partner->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">{{ __('mous.index.filters.status') }}</label>
                    <select name="status" class="form-input">
                        <option value="">{{ __('mous.index.filters.any') }}</option>
                        @foreach($statusOptions as $status => $label)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">{{ __('mous.index.filters.published') }}</label>
                    <select name="published" class="form-input">
                        <option value="">{{ __('mous.index.filters.any') }}</option>
                        <option value="1" @selected(request('published') === '1')>{{ __('common.status.published') }}</option>
                        <option value="0" @selected(request('published') === '0')>{{ __('common.status.draft') }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">{{ __('mous.index.filters.signed_from') }}</label>
                    <input name="signed_from" type="date" value="{{ request('signed_from') }}" class="form-input">
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">{{ __('mous.index.filters.signed_to') }}</label>
                    <input name="signed_to" type="date" value="{{ request('signed_to') }}" class="form-input">
                </div>
                <div class="lg:col-span-5 flex justify-end">
                    <button type="submit" class="btn-primary">
                        {{ __('common.actions.filter') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">{{ __('mous.index.table.partner') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('mous.index.table.title') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('mous.index.table.signed') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('mous.index.table.effective') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('mous.index.table.status') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('mous.index.table.published') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('mous.index.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($mous as $mou)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $mou->partner->display_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $mou->partner->type }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $mou->title }}</div>
                                    @if($mou->reference_no)
                                        <div class="text-xs text-slate-500">{{ $mou->reference_no }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $mou->signed_at?->toDateString() ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    {{ $mou->effective_from?->toDateString() ?? '—' }} – {{ $mou->effective_to?->toDateString() ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-600">
                                            {{ __('mous.statuses.' . $mou->status) }}
                                        </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex h-2 w-2 rounded-full {{ $mou->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.mous.show', $mou) }}" class="text-blue-600 hover:underline text-xs font-semibold">{{ __('common.actions.view') }}</a>
                                        <a href="{{ route('admin.mous.edit', $mou) }}" class="text-blue-600 hover:underline text-xs font-semibold">{{ __('common.actions.edit') }}</a>
                                        <form method="POST" action="{{ route('admin.mous.destroy', $mou) }}" onsubmit="return confirm('{{ __('mous.index.actions.delete_confirm') }}')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:underline text-xs font-semibold">{{ __('common.actions.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-slate-500">
                                    {{ __('mous.index.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($mous->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $mous->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
