@extends('admin.layouts.app')

@section('title', __('admin.profile.update_password_heading'))

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">{{ __('admin.profile.update_password_heading') }}</h3>
            <div class="mt-4 max-w-3xl">
                @include('admin.profile.partials.update-password-form')
            </div>
        </div>
    </div>
@endsection
