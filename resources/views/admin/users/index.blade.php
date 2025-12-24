@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.users') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.labels.roles') }} &amp; {{ __('common.labels.permissions') }}</p>
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
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $user->roles->pluck('name')->implode(', ') ?: 'â€”' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $user->permissions->pluck('name')->implode(', ') ?: 'â€”' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                >
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

