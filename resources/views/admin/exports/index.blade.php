@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.exports') ?? 'Exports' }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.messages.export_hint') ?? 'Generate data exports for reporting.' }}</p>
            </div>
            <a
                href="{{ route('admin.exports.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.create') }}
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.type') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.format') ?? 'Format' }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.requested_at') ?? 'Requested At' }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($exports as $export)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $export->type }}</td>
                            <td class="px-6 py-4 text-slate-500 uppercase">{{ $export->format }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $export->status === 'completed' ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ __('common.status.' . $export->status) ?? ucfirst($export->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ ethiopian_date($export->requested_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'F j, Y g:i A', true) }}</td>
                            <td class="px-6 py-4 text-right">
                                @if ($export->isCompleted() && $export->file_path)
                                    @php
                                        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                                            'admin.exports.download',
                                            now()->addMinutes(10),
                                            ['reportExport' => $export->id]
                                        );
                                    @endphp
                                    <a
                                        href="{{ $url }}"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-800"
                                    >
                                        {{ __('common.actions.download') }}
                                    </a>
                                @else
                                    <span class="text-xs uppercase text-slate-400">{{ __('common.labels.queued') ?? 'Queued' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                {{ __('common.messages.no_exports') ?? 'No exports yet.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $exports->links() }}
        </div>
    </div>
@endsection

