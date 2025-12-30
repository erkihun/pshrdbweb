@extends('admin.layouts.app')

@section('title', __('ui.profile'))

@section('content')
    @php
        $user = Auth::user();
        $department = $user->department_id ? \App\Models\Department::find($user->department_id) : null;
        $profileDetails = [
            __('common.fields.email') => $user->email,
            __('common.fields.phone') => $user->phone ?? __('common.labels.not_available'),
            __('common.fields.national_id') => $user->national_id ?? __('common.labels.not_available'),
            __('common.fields.gender') => $user->gender ?? __('common.labels.not_available'),
            __('common.fields.department') => $department?->name ?? __('common.labels.not_assigned'),
            __('common.fields.roles') => $user->getRoleNames()->implode(', ') ?: __('common.labels.user'),
            __('common.fields.member_since') => $user->created_at?->timezone('Africa/Addis_Ababa')->format('F d, Y') ?? __('common.labels.unknown'),
            __('common.fields.email_verified') => $user->email_verified_at ? __('common.labels.yes') : __('common.labels.no'),
        ];
    @endphp

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-blue to-blue-600 text-xl font-semibold text-white shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-sm font-medium text-slate-500">{{ $user->email }}</p>
                </div>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-2">
                @foreach($profileDetails as $label => $value)
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-slate-500">{{ $label }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">{{ __('common.labels.quick_actions') }}</h3>
            <div class="mt-4 flex flex-wrap gap-4">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-transparent bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:from-blue-700 hover:to-indigo-700"
                >
                    {{ __('common.actions.back_home') }}
                </a>
                <a
                    href="{{ route('admin.profile.update.form') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900"
                >
                    {{ __('common.actions.update_profile') }}
                </a>
                <a
                    href="{{ route('admin.profile.password.form') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900"
                >
                    {{ __('admin.profile.update_password_heading') }}
                </a>
            </div>
        </div>
    </div>
@endsection
