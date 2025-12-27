@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.document_requests') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.messages.manage_document_requests') }}</p>
            </div>
        </div>

        <form class="flex flex-wrap items-end gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="type_id">{{ __('common.labels.request_type') }}</label>
                <select id="type_id" name="type_id" class="form-select">
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @selected(request('type_id') == $type->id)>{{ $type->displayName() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="status">{{ __('common.labels.status') }}</label>
                <select id="status" name="status" class="form-select">
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach (['submitted', 'under_review', 'ready', 'rejected', 'delivered'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ __('common.status.' . $status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="from">{{ __('common.labels.from') }}</label>
                <input id="from" name="from" type="date" value="{{ request('from') }}" class="form-input">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="to">{{ __('common.labels.to') }}</label>
                <input id="to" name="to" type="date" value="{{ request('to') }}" class="form-input">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="search">{{ __('common.actions.search') }}</label>
                <input id="search" name="search" type="text" value="{{ request('search') }}" class="form-input" placeholder="{{ __('common.labels.reference_code') }}">
            </div>
            <div>
                <button type="submit" class="btn-primary">{{ __('common.actions.filter') }}</button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.reference_code') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.request_type') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.full_name') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.submitted_at') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.view') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($documentRequests as $item)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $item->reference_code }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $item->display_type }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $item->full_name }}</td>
                            <td class="px-6 py-4">
                                <span class="badge badge-muted">{{ __('common.status.' . $item->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $item->submitted_at ? ethiopian_date($item->submitted_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) : '' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.document-requests.show', $item) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">{{ __('common.actions.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">{{ __('common.messages.no_document_requests') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $documentRequests->links() }}
        </div>
    </div>
@endsection

