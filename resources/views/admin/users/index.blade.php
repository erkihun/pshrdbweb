@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.users') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.labels.roles') }} &amp; {{ __('common.labels.permissions') }}</p>
            </div>
            {{-- Remove the "Add User" button since route doesn't exist --}}
        </div>

        {{-- Simple Stats --}}
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-sm font-medium text-slate-600">Total Users</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $users->total() }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-sm font-medium text-slate-600">Admins</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $users->where('is_admin', true)->count() }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-sm font-medium text-slate-600">Active</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $users->where('is_active', true)->count() }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-sm font-medium text-slate-600">New This Month</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.title') }}</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">{{ __('common.labels.roles') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.permissions') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.edit') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-700">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($user->roles as $role)
                                        <span class="inline-block rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-700">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-slate-400">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $user->permissions->pluck('name')->implode(', ') ?: '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('common.actions.edit') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                {{ __('common.messages.no_users') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $users->links() }}
        </div>
    </div>
@endsection