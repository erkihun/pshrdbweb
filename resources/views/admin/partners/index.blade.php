@extends('admin.layouts.app')

@section('content')
    @php
        $statusOptions = [
            '' => __('partners.index.filters.all'),
            '1' => __('partners.statuses.active'),
            '0' => __('partners.statuses.inactive'),
        ];
    @endphp
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white shadow-lg">
            <div>
                <h1 class="text-2xl font-bold">{{ __('partners.index.title') }}</h1>
                <p class="text-sm text-slate-200">{{ __('partners.index.description') }}</p>
            </div>
            <a href="{{ route('admin.partners.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                {{ __('common.actions.create') }}
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="text-sm font-semibold text-slate-700">{{ __('partners.index.filters.search') }}</label>
                    <input
                        name="q"
                        type="text"
                        value="{{ request('q') }}"
                        class="form-input"
                        placeholder="{{ __('partners.index.filters.placeholder') }}"
                    >
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">{{ __('partners.index.filters.status') }}</label>
                    <select name="is_active" class="form-input">
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(request('is_active') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
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
                            <th class="px-4 py-3 text-left">{{ __('partners.index.table.partner') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('partners.index.table.type') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('partners.index.table.location') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('partners.index.table.contact') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('partners.index.table.status') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('partners.index.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($partners as $partner)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $partner->display_name }}</div>
                                    @if($partner->short_name)
                                        <div class="text-xs text-slate-500">{{ $partner->short_name }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 capitalize">{{ __('partners.types.' . $partner->type) }}</td>
                                <td class="px-4 py-3">
                                    <div>{{ $partner->city ?? '—' }}</div>
                                    <div class="text-xs text-slate-500">{{ $partner->country }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($partner->email)
                                        <div>{{ $partner->email }}</div>
                                    @endif
                                    @if($partner->phone)
                                        <div>{{ $partner->phone }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $partner->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ __('partners.statuses.' . ($partner->is_active ? 'active' : 'inactive')) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.partners.show', $partner) }}" class="text-blue-600 hover:underline text-xs font-semibold">{{ __('common.actions.view') }}</a>
                                        <a href="{{ route('admin.partners.edit', $partner) }}" class="text-blue-600 hover:underline text-xs font-semibold">{{ __('common.actions.edit') }}</a>
                                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" onsubmit="return confirm('{{ __('partners.index.actions.delete_confirm') }}')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:underline text-xs font-semibold">{{ __('common.actions.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">
                                    {{ __('partners.index.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($partners->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $partners->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
