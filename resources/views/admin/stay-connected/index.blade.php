@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
                <h1 class="text-2xl font-semibold text-slate-900">Stay connected</h1>
                <p class="text-sm text-slate-500">Manage cards shown on public home "Stay connected" section.</p>
            </div>
            <a href="{{ route('admin.stay-connected.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                {{ __('common.actions.create') }}
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Title</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Embed URL</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Order</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-600">{{ __('common.actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items as $item)
                            <tr>
                                <td class="px-4 py-3 text-slate-800">{{ $item->display_title }}</td>
                                <td class="px-4 py-3 text-slate-600">
                                    <span class="block max-w-md truncate">{{ $item->embed_url }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-700">{{ $item->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route('admin.stay-connected.edit', $item) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                            {{ __('common.actions.edit') }}
                                        </a>
                                        <form method="POST" action="{{ route('admin.stay-connected.destroy', $item) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700" onclick="return confirm('Delete this item?')">
                                                {{ __('common.actions.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No stay connected items yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection
