@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.actions.create') }}</h1>
            <p class="text-sm text-slate-500">{{ __('common.nav.news') }} / {{ __('common.nav.announcements') }}</p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.posts.store') }}"
            enctype="multipart/form-data"
            class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf

            @include('admin.posts._form')

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.posts.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    {{ __('common.actions.cancel') }}
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

