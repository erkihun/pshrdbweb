@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('vacancies.application.announcement_edit_title', ['title' => $announcement->display_title]) }}</h1>
            <p class="text-sm text-slate-500">{{ __('vacancies.application.announcement_edit_description') }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.posts._form', ['fixedType' => 'announcement', 'post' => $announcement])
                <div class="flex flex-wrap items-center gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                    >
                        {{ __('common.actions.update') }}
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">{{ __('common.actions.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
