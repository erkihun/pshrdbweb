@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Signage Templates</h1>
                <p class="text-sm text-slate-500">Design the layout and partitions used across signage displays</p>
            </div>
            <a
                href="{{ route('admin.signage.templates.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                Create Template
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Layout</th>
                            <th class="px-6 py-3 text-left">Orientation</th>
                            <th class="px-6 py-3 text-left">Displays</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($templates as $template)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <a
                                        href="{{ route('admin.signage.templates.show', $template) }}"
                                        class="text-sm font-semibold text-slate-900 hover:text-blue-600"
                                    >
                                        {{ $template->name_en ?: $template->name_am ?: '—' }}
                                    </a>
                                    <p class="text-xs text-slate-500">{{ $template->slug }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Illuminate\Support\Str::of($template->layout)->replace('_', ' ')->title() }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ ucfirst($template->orientation) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $template->displays_count }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $template->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a
                                            href="{{ route('admin.signage.templates.edit', $template) }}"
                                            class="text-xs font-semibold text-blue-600 hover:text-blue-800"
                                        >
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.signage.templates.destroy', $template) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-xs font-semibold text-rose-600 hover:text-rose-800"
                                                onclick="return confirm('Delete this template?')"
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
                                    No signage templates found. Start by creating one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($templates->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
                {{ $templates->links() }}
            </div>
        @endif
    </div>
@endsection
