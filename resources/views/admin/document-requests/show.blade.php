@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.document_request') }}</h1>
                <p class="text-sm text-slate-500">{{ $documentRequest->reference_code }}</p>
            </div>
            <a href="{{ route('admin.document-requests.index') }}" class="btn-secondary">{{ __('common.actions.back') }}</a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.full_name') }}</div>
                    <div class="text-sm text-slate-900">{{ $documentRequest->full_name }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.phone') }}</div>
                    <div class="text-sm text-slate-900">{{ $documentRequest->phone }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.email') }}</div>
                    <div class="text-sm text-slate-900">{{ $documentRequest->email ?? '-' }}</div>
                </div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.request_type') }}</div>
                <div class="text-sm text-slate-900">{{ $documentRequest->display_type }}</div>
            </div>
            <div>
                <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.status') }}</div>
                <div class="text-sm text-slate-900">{{ __('common.status.' . $documentRequest->status) }}</div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.address') }}</div>
                    <div class="text-sm text-slate-700">{{ $documentRequest->address }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.purpose') }}</div>
                    <div class="text-sm text-slate-700">{{ $documentRequest->purpose }}</div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.attachment') }}</span>
                @if ($documentRequest->attachment_path)
                    <a href="{{ route('admin.document-requests.attachment', $documentRequest) }}" class="text-blue-600 hover:text-blue-800">{{ __('common.actions.download') }}</a>
                @else
                    <span class="text-sm text-slate-500">{{ __('common.messages.no_attachment') }}</span>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.document-requests.update', $documentRequest) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="status">{{ __('common.labels.status') }}</label>
                    <select id="status" name="status" class="form-select">
                        @foreach (['submitted', 'under_review', 'ready', 'rejected', 'delivered'] as $status)
                            <option value="{{ $status }}" @selected($documentRequest->status === $status)>{{ __('common.status.' . $status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="admin_note">{{ __('common.labels.admin_note') }}</label>
                    <textarea id="admin_note" name="admin_note" rows="3" class="form-textarea">{{ old('admin_note', $documentRequest->admin_note) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">{{ __('common.actions.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

