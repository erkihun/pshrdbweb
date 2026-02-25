@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white shadow-lg">
                <div>
                    <h1 class="text-2xl font-bold">{{ __('partners.edit.title') }}</h1>
                    <p class="text-sm text-slate-200">{{ __('partners.edit.description') }}</p>
                </div>
            <a href="{{ route('admin.partners.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                {{ __('partners.actions.back') }}
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.partners._form')
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        {{ __('partners.edit.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
