@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-1">
                <p class="text-xs uppercase tracking-wide text-brand-muted">Users</p>
                <h1 class="text-2xl font-semibold text-brand-ink">Create new user</h1>
                <p class="text-sm text-slate-500">Provide the user's contact info and assign the appropriate department and role.</p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('admin.users.store') }}"
            enctype="multipart/form-data"
            class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="name">Full name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="email">Email address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="phone">Phone</label>
                    <input
                        id="phone"
                        name="phone"
                        type="text"
                        value="{{ old('phone') }}"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                    >
                    @error('phone')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="national_id">National ID</label>
                    <input
                        id="national_id"
                        name="national_id"
                        type="text"
                        value="{{ old('national_id') }}"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                    >
                    @error('national_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="gender">Gender</label>
                    <select
                        id="gender"
                        name="gender"
                        class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                    >
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="department_id">Department</label>
                    <select
                        id="department_id"
                        name="department_id"
                        class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                    >
                        <option value="">Select department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="avatar">Avatar</label>
                    <input
                        id="avatar"
                        name="avatar"
                        type="file"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-700 focus:border-brand-blue focus:outline-none"
                    >
                    @error('avatar')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="password">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                        required
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="password_confirmation">Confirm password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                        required
                    >
                </div>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">Roles</h2>
                <p class="text-xs text-slate-500">Assign predefined role(s) to define permissions.</p>
                <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($roles as $role)
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 transition hover:border-brand-blue hover:bg-brand-blue/5">
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                class="h-4 w-4 rounded border-slate-300 text-brand-blue focus:ring-brand-blue"
                                {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}
                            >
                            <span>{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-brand-blue hover:text-brand-blue/80">Cancel</a>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-2xl bg-brand-blue px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-blue/80"
                >
                    Save user
                </button>
            </div>
        </form>
    </div>
@endsection
