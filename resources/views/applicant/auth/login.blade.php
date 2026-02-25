@extends('layouts.public')

@section('content')
    <div class="mx-auto max-w-md space-y-6 py-12">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">Applicant access</p>
                <h1 class="text-2xl font-bold text-slate-900">Applicant login</h1>
                <p class="text-sm text-slate-500">Sign in to manage your vacancy applications.</p>
            </div>

            <form method="POST" action="{{ route('applicant.login.submit') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required
                        autocomplete="email"
                    >
                    @error('email')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required
                        autocomplete="current-password"
                    >
                    @error('password')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300">
                        Remember me
                    </label>
                    <a href="{{ route('vacancies.track') }}" class="font-semibold text-blue-600 hover:text-blue-500">Track application</a>
                </div>
                <button type="submit" class="w-full rounded-full bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Sign in
                </button>
            </form>
        </div>
    </div>
@endsection
