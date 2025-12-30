@extends('admin.layouts.app')

@section('title', __('admin.profile.section_title'))

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-slate-900">{{ __('admin.profile.information_heading') }}</h3>
                <div class="max-w-3xl">
                    @include('admin.profile.partials.update-profile-information-form', ['redirectRoute' => 'admin.profile.update.form'])
                </div>
            </div>
        </div>
    </div>
@endsection
