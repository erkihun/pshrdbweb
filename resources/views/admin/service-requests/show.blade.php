@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $serviceRequest->reference_code }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.labels.service_request') }}</p>
            </div>
            <a href="{{ route('admin.service-requests.index') }}" class="btn-secondary">{{ __('common.actions.back') }}</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.subject') }}</h2>
                    <p class="mt-2 text-sm text-slate-700">{{ $serviceRequest->subject }}</p>

                    <h3 class="mt-4 text-sm font-semibold text-slate-900">{{ __('common.labels.description') }}</h3>
                    <p class="mt-2 whitespace-pre-line text-sm text-slate-700">{{ $serviceRequest->description }}</p>

                    @if ($serviceRequest->attachment_path)
                        <div class="mt-4">
                            <a href="{{ asset('storage/' . $serviceRequest->attachment_path) }}" class="btn-secondary" target="_blank" rel="noopener">{{ __('common.labels.attachment') }}</a>
                        </div>
                    @endif
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.update') }}</h2>
                    <form method="POST" action="{{ route('admin.service-requests.update', $serviceRequest) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="status">{{ __('common.labels.status') }}</label>
                            <select id="status" name="status" class="form-select">
                                @foreach (['submitted','under_review','approved','rejected','completed'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $serviceRequest->status) === $status)>{{ __('common.status.' . $status) }}</option>
                                @endforeach
                            </select>
                            @error('status')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="admin_note">{{ __('common.labels.admin_note') }}</label>
                            <textarea id="admin_note" name="admin_note" rows="4" class="form-textarea">{{ old('admin_note', $serviceRequest->admin_note) }}</textarea>
                            @error('admin_note')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="attachment">{{ __('common.labels.attachment') }}</label>
                            <input id="attachment" name="attachment" type="file" class="mt-2 block w-full text-sm">
                            @error('attachment')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">{{ __('common.actions.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.details') }}</h2>
                    <dl class="mt-4 space-y-3 text-sm text-slate-700">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.full_name') }}</dt>
                            <dd class="mt-1">{{ $serviceRequest->full_name }}</dd>
                        </div>
                        @if ($serviceRequest->phone)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.phone') }}</dt>
                                <dd class="mt-1">{{ $serviceRequest->phone }}</dd>
                            </div>
                        @endif
                        @if ($serviceRequest->email)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.email') }}</dt>
                                <dd class="mt-1">{{ $serviceRequest->email }}</dd>
                            </div>
                        @endif
                        @if ($serviceRequest->service)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.nav.services') }}</dt>
                                <dd class="mt-1">{{ $serviceRequest->service->display_title }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.status') }}</dt>
                            <dd class="mt-1">{{ __('common.status.' . $serviceRequest->status) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.created_at') }}</dt>
                            <dd class="mt-1">{{ $serviceRequest->submitted_at ? ethiopian_date($serviceRequest->submitted_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) : '' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.last_updated') }}</dt>
                            <dd class="mt-1">{{ $serviceRequest->updated_at ? ethiopian_date($serviceRequest->updated_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) : '' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

