@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Create Signage Display</h1>
            <p class="text-sm text-slate-500">Choose a template, add content, and publish when ready.</p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.signage.displays.store') }}"
            class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf

            @include('admin.signage.displays._form')

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.signage.displays.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    Save Display
                </button>
            </div>
        </form>
    </div>
@endsection
