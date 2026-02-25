@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.service_feedback') }}</h1>
                <p class="text-sm text-slate-500">{{ $serviceFeedback->service?->display_title }}</p>
            </div>
            <a href="{{ route('admin.service-feedback.index') }}" class="btn-secondary">{{ __('common.actions.back') }}</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        <span>{{ __('common.labels.rating') }}:</span>
                        <span>{{ $serviceFeedback->rating }}/5</span>
                    </div>
                    @if ($serviceFeedback->comment)
                        <div class="mt-4 text-sm text-slate-700 whitespace-pre-line">{{ $serviceFeedback->comment }}</div>
                    @endif
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <form method="POST" action="{{ route('admin.service-feedback.update', $serviceFeedback) }}" class="flex items-center gap-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_approved" value="0">
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="is_approved" value="1" class="h-4 w-4 rounded border-slate-300 text-slate-900" @checked($serviceFeedback->is_approved)>
                            {{ __('common.labels.approved') }}
                        </label>
                        <button type="submit" class="btn-primary">{{ __('common.actions.save') }}</button>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.details') }}</h2>
                    <dl class="mt-4 space-y-3 text-sm text-slate-700">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.full_name') }}</dt>
                            <dd class="mt-1">{{ $serviceFeedback->full_name ?? __('common.labels.anonymous') }}</dd>
                        </div>
                        @if ($serviceFeedback->phone)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.phone') }}</dt>
                                <dd class="mt-1">{{ $serviceFeedback->phone }}</dd>
                            </div>
                        @endif
                        @if ($serviceFeedback->email)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.email') }}</dt>
                                <dd class="mt-1">{{ $serviceFeedback->email }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.locale') }}</dt>
                            <dd class="mt-1">{{ $serviceFeedback->locale }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.created_at') }}</dt>
                            <dd class="mt-1">{{ $serviceFeedback->submitted_at ? ethiopian_date($serviceFeedback->submitted_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) : '' }}</dd>
                        </div>
                    </dl>
                </div>

                <form method="POST" action="{{ route('admin.service-feedback.destroy', $serviceFeedback) }}" onsubmit="return confirm('Delete feedback?')" class="rounded-xl border border-rose-200 bg-rose-50 p-6 shadow-sm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger w-full justify-center">{{ __('common.actions.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

