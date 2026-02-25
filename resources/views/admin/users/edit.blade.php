@extends('admin.layouts.app')

@section('content')
@php
        $selectedRoles = old('roles', $user->roles->pluck('name')->toArray());
        $currentAvatar = $user->avatar_path ? asset('storage/' . $user->avatar_path) : null;
        $formattedNationalId = trim(chunk_split(old('national_id', $user->national_id ?? ''), 4, ' '));
    @endphp

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('admin.users.section_label') }}</p>
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-semibold text-slate-900">{{ __('admin.users.edit_title') }}</h1>
                        <p class="text-sm text-slate-500">{{ __('admin.users.edit_description') }}</p>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('admin.users.user_id', ['id' => $user->id]) }}</span>
                </div>
                <div class="mt-4 flex items-center gap-3 text-sm text-slate-500">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">{{ $user->name }}</p>
                        <p>{{ $user->email }}</p>
                    </div>
                    @if($currentAvatar)
                        <img src="{{ $currentAvatar }}" alt="avatar" class="h-10 w-10 rounded-full object-cover" />
                    @endif
                </div>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('admin.users.update', $user) }}"
            enctype="multipart/form-data"
            class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf
            @method('PUT')

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="name">{{ __('admin.users.full_name') }}</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="email">{{ __('admin.users.email_address') }}</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
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
                    <label class="text-sm font-semibold text-slate-900" for="phone">{{ __('admin.users.phone') }}</label>
                    <input
                        id="phone"
                        name="phone"
                        type="text"
                        value="{{ old('phone', $user->phone) }}"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                    >
                    @error('phone')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="national_id">{{ __('admin.users.national_id') }}</label>
                    <input
                        id="national_id"
                        name="national_id"
                        type="text"
                        value="{{ $formattedNationalId }}"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none national-id-field"
                        inputmode="numeric"
                        maxlength="19"
                        pattern="[0-9 ]*"
                    >
                    @error('national_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-4">
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="gender">{{ __('admin.users.gender') }}</label>
                    <select
                        id="gender"
                        name="gender"
                        class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                    >
                        <option value="">{{ __('admin.users.select_gender') }}</option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>{{ __('admin.gender_options.male') }}</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>{{ __('admin.gender_options.female') }}</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="department_id">{{ __('admin.users.department') }}</label>
                    <select
                        id="department_id"
                        name="department_id"
                        class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                    >
                        <option value="">{{ __('admin.users.select_department') }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="organization_id">Organization</label>
                    <select
                        id="organization_id"
                        name="organization_id"
                        class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                    >
                        <option value="">Select organization</option>
                        @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}" {{ old('organization_id', $user->organization_id) == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('organization_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="avatar">{{ __('admin.users.avatar') }}</label>
                    <input
                        id="avatar"
                        name="avatar"
                        type="file"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-700 focus:border-brand-blue focus:outline-none"
                    >
                    @error('avatar')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    @if($currentAvatar)
                        <p class="mt-2 text-xs text-slate-500">{{ __('admin.users.current_avatar_note') }}</p>
                    @endif
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="password">{{ __('admin.users.password') }}</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                        placeholder="{{ __('admin.users.password_placeholder') }}"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-900" for="password_confirmation">{{ __('admin.users.confirm_password') }}</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none"
                        placeholder="{{ __('admin.users.confirm_password_placeholder') }}"
                    >
                </div>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">{{ __('admin.users.roles_title') }}</h2>
                <p class="text-xs text-slate-500">{{ __('admin.users.roles_description') }}</p>
                <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($roles as $role)
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 transition hover:border-brand-blue hover:bg-brand-blue/5">
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                class="h-4 w-4 rounded border-slate-300 text-brand-blue focus:ring-brand-blue"
                                {{ in_array($role->name, $selectedRoles) ? 'checked' : '' }}
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
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-brand-blue hover:text-brand-blue/80">{{ __('admin.users.cancel') }}</a>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-2xl bg-brand-blue px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-blue/80"
                >
                    {{ __('admin.users.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
