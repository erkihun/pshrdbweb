@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('ui.tenders_procurement') }}</h1>
                <p class="text-sm text-slate-500">Create, publish, and manage procurement notices.</p>
            </div>
            <a href="{{ route('admin.tenders.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                {{ __('common.actions.create') ?? 'Create Tender' }}
            </a>
        </div>

        <form method="GET" class="grid gap-3 md:grid-cols-3">
            <div>
                <label class="sr-only">Search</label>
                <input name="q" value="{{ request('q') }}" type="text" placeholder="Search title" class="w-full rounded-lg border border-slate-200 bg-white/90 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="sr-only">Status</label>
                <select name="status" class="w-full rounded-lg border border-slate-200 bg-white/90 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full rounded-lg bg-gradient-to-r from-blue-600 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm">Filter</button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Title</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Budget</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Published</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($tenders as $tender)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $tender->title }}</p>
                                <p class="text-xs text-slate-400">{{ \Illuminate\Support\Str::limit($tender->description, 80) }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-900">
                                {{ $tender->budget ? number_format($tender->budget, 2) : '—' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $tender->status === 'open' ? 'bg-emerald-100 text-emerald-800' : ($tender->status === 'awarded' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-800') }}">
                                    {{ ucfirst($tender->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-500">
                                {{ optional($tender->published_at)->format('M d, Y') ?? 'Draft' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.tenders.edit', $tender) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">Edit</a>
                                    <form method="POST" action="{{ route('admin.tenders.destroy', $tender) }}" onsubmit="return confirm('Remove this tender?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-sm text-slate-500">
                                No tenders found yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between text-sm text-slate-500">
            <p>{{ $tenders->total() }} tenders</p>
            {{ $tenders->links() }}
        </div>
    </div>
@endsection
