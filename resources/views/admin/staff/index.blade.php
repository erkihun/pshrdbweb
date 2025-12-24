@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.staff') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.nav.organization') }}</p>
            </div>
            <a
                href="{{ route('admin.staff.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.create') }}
            </a>
        </div>

        <form class="flex flex-wrap items-end gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="department_id">{{ __('common.labels.department') }}</label>
                <select
                    id="department_id"
                    name="department_id"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>
                            {{ $department->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="q">{{ __('common.actions.search') }}</label>
                <input
                    id="q"
                    name="q"
                    type="text"
                    value="{{ request('q') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
            </div>
            <div>
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.filter') }}
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.title') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.department') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.edit') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($staff as $member)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $member->display_name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $member->department?->display_name ?? 'â€”' }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $member->is_active ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $member->is_active ? __('common.status.active') : __('common.status.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.staff.edit', $member) }}"
                                    class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                >
                                    {{ __('common.actions.edit') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                {{ __('common.messages.no_staff') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $staff->links() }}
        </div>
    </div>
@endsection

