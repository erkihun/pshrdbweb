@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.service_feedback') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.actions.filter') }}</p>
            </div>
        </div>

        <form class="flex flex-wrap items-end gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
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
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="rating">{{ __('common.labels.rating') }}</label>
                <select id="rating" name="rating" class="form-select">
                    <option value="">{{ __('common.labels.all') }}</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="approved">{{ __('common.labels.status') }}</label>
                <select id="approved" name="approved" class="form-select">
                    <option value="">{{ __('common.labels.all') }}</option>
                    <option value="1" @selected(request('approved') === '1')>Approved</option>
                    <option value="0" @selected(request('approved') === '0')>Pending</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn-primary">{{ __('common.actions.filter') }}</button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.nav.services') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.rating') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.full_name') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.created_at') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.view') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($feedback as $item)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $item->service?->display_title }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->rating }}/5</td>
                            <td class="px-6 py-4 text-slate-500">{{ $item->full_name ?? __('common.labels.anonymous') }}</td>
                            <td class="px-6 py-4">
                                <span class="badge badge-muted">{{ $item->is_approved ? 'Approved' : 'Pending' }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $item->submitted_at ? ethiopian_date($item->submitted_at, 'dd MMMM yyyy', 'Africa/Addis_Ababa', null, 'M d, Y', true) : '' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.service-feedback.show', $item) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">{{ __('common.actions.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">{{ __('common.messages.no_feedback') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $feedback->links() }}
        </div>
    </div>
@endsection

