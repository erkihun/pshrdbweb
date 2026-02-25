@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.service_requests') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.actions.filter') }}</p>
            </div>
        </div>

        <form class="flex flex-wrap items-end gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="status">{{ __('common.labels.status') }}</label>
                <select id="status" name="status" class="form-select">
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach (['submitted','under_review','approved','rejected','completed'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ __('common.status.' . $status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="service_id">{{ __('common.nav.services') }}</label>
                <select id="service_id" name="service_id" class="form-select">
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(request('service_id') == $service->id)>{{ $service->display_title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="from">From</label>
                <input id="from" name="from" type="date" value="{{ request('from') }}" class="form-input">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="to">To</label>
                <input id="to" name="to" type="date" value="{{ request('to') }}" class="form-input">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="q">{{ __('common.actions.search') }}</label>
                <input id="q" name="q" type="text" value="{{ request('q') }}" class="form-input" placeholder="Ref / phone / name">
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
                        <th class="px-6 py-3">{{ __('common.labels.full_name') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.subject') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.created_at') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.view') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($serviceRequests as $requestItem)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $requestItem->reference_code }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $requestItem->full_name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $requestItem->subject }}</td>
                            <td class="px-6 py-4">
                                <span class="badge badge-muted">{{ __('common.status.' . $requestItem->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $requestItem->submitted_at ? ethiopian_date($requestItem->submitted_at, 'dd MMMM yyyy', 'Africa/Addis_Ababa', null, 'M d, Y', true) : '' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.service-requests.show', $requestItem) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">{{ __('common.actions.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">{{ __('common.messages.no_requests') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $serviceRequests->links() }}
        </div>
    </div>
@endsection

