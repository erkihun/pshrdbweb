@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Signage Displays</h1>
                <p class="text-sm text-slate-500">Schedule and publish portrait screens for waiting areas.</p>
            </div>
            <a
                href="{{ route('admin.signage.displays.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                Create Display
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-left">Title</th>
                            <th class="px-6 py-3 text-left">Template</th>
                            <th class="px-6 py-3 text-left">Refresh</th>
                            <th class="px-6 py-3 text-left">Published</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($displays as $display)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <a
                                        href="{{ route('admin.signage.displays.show', $display) }}"
                                        class="text-sm font-semibold text-slate-900 hover:text-blue-600"
                                    >
                                        {{ $display->title_en ?: $display->title_am ?: 'Untitled display' }}
                                    </a>
                                    <p class="text-xs text-slate-500">{{ $display->slug }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $display->template->name_en ?: $display->template->name_am ?: '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    Every {{ $display->refresh_seconds }}s
                                </td>
                                <td class="px-6 py-4">
                                    {{ $display->published_at?->format('d M Y H:i') ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $display->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $display->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a
                                            href="{{ route('admin.signage.displays.show', $display) }}"
                                            class="text-xs font-semibold text-slate-600 hover:text-slate-900"
                                        >
                                            View
                                        </a>
                                        <a
                                            href="{{ route('admin.signage.displays.edit', $display) }}"
                                            class="text-xs font-semibold text-blue-600 hover:text-blue-800"
                                        >
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.signage.displays.destroy', $display) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-xs font-semibold text-rose-600 hover:text-rose-800"
                                                onclick="return confirm('Delete this display?')"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                                    Nothing to show yet. Create a display to start rolling signage content.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($displays->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
                {{ $displays->links() }}
            </div>
        @endif
    </div>
@endsection
