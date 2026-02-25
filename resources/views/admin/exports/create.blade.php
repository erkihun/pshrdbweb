@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.actions.create') }} {{ __('common.labels.exports') ?? 'Export' }}</h1>
            <p class="text-sm text-slate-500">{{ __('common.messages.export_create_hint') ?? 'Select the data set and format to export.' }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.exports.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="type">{{ __('common.labels.type') }}</label>
                    <select
                        id="type"
                        name="type"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        required
                    >
                        <option value="">{{ __('common.actions.choose') }}</option>
                        <option value="tickets" @selected(old('type') === 'tickets')>Tickets</option>
                        <option value="service_requests" @selected(old('type') === 'service_requests')>Service Requests</option>
                        <option value="vacancy_applications" @selected(old('type') === 'vacancy_applications')>Vacancy Applications</option>
                        <option value="appointments" @selected(old('type') === 'appointments')>Appointments</option>
                        <option value="subscribers" @selected(old('type') === 'subscribers')>Subscribers</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="format">{{ __('common.labels.format') ?? 'Format' }}</label>
                    <select
                        id="format"
                        name="format"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        required
                    >
                        <option value="">{{ __('common.actions.choose') }}</option>
                        <option value="csv" @selected(old('format') === 'csv')>CSV</option>
                        <option value="excel" @selected(old('format') === 'excel')>Excel</option>
                        <option value="pdf" @selected(old('format') === 'pdf')>PDF Summary</option>
                    </select>
                    @error('format')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="from">{{ __('common.labels.publish_date') }} (from)</label>
                        <input
                            id="from"
                            name="from"
                            type="date"
                            value="{{ old('from') }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        >
                        @error('from')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="to">{{ __('common.labels.publish_date') }} (to)</label>
                        <input
                            id="to"
                            name="to"
                            type="date"
                            value="{{ old('to') }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        >
                        @error('to')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700" for="status">{{ __('common.labels.status') }}</label>
                    <input
                        id="status"
                        name="status"
                        type="text"
                        value="{{ old('status') }}"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        placeholder="status value (optional)"
                    >
                    @error('status')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        {{ __('common.actions.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

