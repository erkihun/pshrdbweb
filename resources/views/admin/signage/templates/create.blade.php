@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Create Signage Template</h1>
            <p class="text-sm text-slate-500">Define the layout and schema that signage displays will use.</p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.signage.templates.store') }}"
            class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf

            @include('admin.signage.templates._form')

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.signage.templates.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    Save Template
                </button>
            </div>
        </form>
    </div>
@endsection
