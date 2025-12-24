@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.actions.edit') }}</h1>
            <p class="text-sm text-slate-500">{{ $user->name }} Â· {{ $user->email }}</p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.users.update', $user) }}"
            class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf
            @method('PUT')

            <div>
                <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.roles') }}</h2>
                <div class="mt-3 grid gap-3 sm:grid-cols-3">
                    @foreach ($roles as $role)
                        <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                                @checked($user->roles->contains('name', $role->name))
                            >
                            <span class="text-slate-700">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.permissions') }}</h2>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->name }}"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                                @checked($user->permissions->contains('name', $permission->name))
                            >
                            <span class="text-slate-700">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('permissions')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    {{ __('common.actions.back') }}
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

