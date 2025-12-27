@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.chat_support') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.messages.chat_details') }}</p>
            </div>
            <a href="{{ route('admin.chats.index') }}" class="btn-secondary">{{ __('common.actions.back') }}</a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.visitor') }}</div>
                    <div class="text-sm text-slate-900">{{ $chatSession->visitor_name ?? __('common.labels.anonymous') }}</div>
                    <div class="mt-1 text-xs text-slate-500">{{ $chatSession->visitor_email ?? __('common.labels.email') }}</div>
                    <div class="text-xs text-slate-500">{{ $chatSession->visitor_phone ?? __('common.labels.phone') }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.status') }}</div>
                    <div class="text-sm text-slate-900">{{ $chatSession->status }}</div>
                    <div class="text-xs text-slate-500">{{ $chatSession->started_at ? ethiopian_date($chatSession->started_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, H:i', true) : '' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.assigned_to') }}</div>
                    <div class="text-sm text-slate-900">{{ $chatSession->assignedTo?->name ?? __('common.labels.unassigned') }}</div>
                </div>
            </div>

            <form action="{{ route('admin.chats.update', $chatSession) }}" method="POST" class="mt-6 grid gap-4 md:grid-cols-3">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-500" for="assigned_to">{{ __('common.labels.agent') }}</label>
                    <select id="assigned_to" name="assigned_to" class="form-select w-full">
                        <option value="">{{ __('common.labels.unassigned') }}</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" @selected($chatSession->assigned_to === $agent->id)>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500" for="status">{{ __('common.labels.status') }}</label>
                    <select id="status" name="status" class="form-select w-full">
                        @foreach (['waiting', 'active', 'closed'] as $status)
                            <option value="{{ $status }}" @selected($chatSession->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end justify-end">
                    <button type="submit" class="btn-primary w-full">{{ __('common.actions.save') }}</button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">{{ __('common.labels.messages') }}</h2>
            <div class="mt-4 space-y-4">
                @foreach ($chatSession->messages as $message)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex items-center justify-between text-xs uppercase tracking-wide text-slate-400">
                            <span>{{ $message->sender_type === 'agent' ? __('common.labels.agent') : __('common.labels.visitor') }}</span>
                            <span>{{ $message->sent_at ? ethiopian_date($message->sent_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, H:i', true) : '' }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-700">{{ $message->message }}</p>
                    </div>
                @endforeach
            </div>
            <form action="{{ route('admin.chats.messages.store', $chatSession) }}" method="POST" class="mt-6 space-y-3">
                @csrf
                <label class="text-xs font-semibold text-slate-500" for="message">{{ __('common.labels.message') }}</label>
                <textarea id="message" name="message" rows="3" class="form-textarea w-full"></textarea>
                <div class="text-right">
                    <button type="submit" class="btn-primary">{{ __('common.actions.send') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

