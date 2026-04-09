@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Vacancy Announcements</p>
                <h1 class="text-2xl font-semibold text-slate-900">Manage Vacancy Announcements</h1>
                <p class="text-sm text-slate-500">Create, view, edit, and delete vacancy announcements.</p>
            </div>
            <a href="{{ route('admin.vacancies.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500">
                Create Announcement
            </a>
        </div>

        <form method="GET" class="grid gap-3 md:grid-cols-[1fr,180px]">
            <div>
                <label class="sr-only" for="q">{{ __('common.actions.search') }}</label>
                <input
                    id="q"
                    name="q"
                    value="{{ request('q') }}"
                    type="text"
                    placeholder="Search title"
                    class="w-full rounded-lg border border-slate-200 bg-white/90 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>
            <div>
                <button type="submit" class="w-full rounded-lg bg-gradient-to-r from-blue-600 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                    {{ __('common.actions.filter') }}
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Title</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Created</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.actions.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($vacancies as $vacancy)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 text-sm text-slate-700">{{ optional($vacancy->deadline)->format('M d, Y') ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $vacancy->title }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $vacancy->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $vacancy->is_published ? 'Published' : 'Unpublished' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-500">{{ optional($vacancy->created_at)->format('M d, Y') ?? '-' }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('admin.vacancies.show', $vacancy) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">{{ __('common.actions.view') }}</a>
                                    <a href="{{ route('admin.vacancies.edit', $vacancy) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-800">{{ __('common.actions.edit') }}</a>
                                    <form method="POST" action="{{ route($vacancy->is_published ? 'admin.vacancies.unpublish' : 'admin.vacancies.publish', $vacancy) }}">
                                        @csrf
                                        <button type="submit" class="text-sm font-semibold {{ $vacancy->is_published ? 'text-amber-700 hover:text-amber-800' : 'text-emerald-700 hover:text-emerald-800' }}">
                                            {{ $vacancy->is_published ? 'Unpublish' : 'Publish' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.vacancies.destroy', $vacancy) }}" onsubmit="return confirm('{{ __('vacancies.admin.delete_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-500">{{ __('common.actions.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-sm text-slate-500">No vacancy announcements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between text-sm text-slate-500">
            <p>{{ $vacancies->total() }} announcements</p>
            {{ $vacancies->links() }}
        </div>
    </div>
@endsection
