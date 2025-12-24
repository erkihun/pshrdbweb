@extends('admin.layouts.app')

@section('content')
    <x-admin.card class="space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs text-brand-muted">Overview</p>
                <h1 class="text-2xl font-semibold text-brand-ink">Welcome back, {{ Auth::user()->name }}.</h1>
            </div>
            <x-admin.button variant="primary" type="button" class="rounded-full px-5 py-2 text-[11px]">
                Generate report
            </x-admin.button>
        </div>
    </x-admin.card>

    <div class="grid gap-4 md:grid-cols-4">
        <x-admin.card class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-brand-muted">Published posts</p>
                <x-admin.badge type="warning">Updated</x-admin.badge>
            </div>
            <p class="text-3xl font-semibold text-brand-ink">{{ number_format($postCount) }}</p>
            <p class="text-xs text-brand-muted">News &amp; announcements</p>
        </x-admin.card>

        <x-admin.card class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-brand-muted">Active services</p>
                <x-admin.badge type="success">Live</x-admin.badge>
            </div>
            <p class="text-3xl font-semibold text-brand-ink">{{ number_format($serviceCount) }}</p>
            <p class="text-xs text-brand-muted">Citizen services</p>
        </x-admin.card>

        <x-admin.card class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-brand-muted">Published documents</p>
                <x-admin.badge type="warning">New</x-admin.badge>
            </div>
            <p class="text-3xl font-semibold text-brand-ink">{{ number_format($documentCount) }}</p>
            <p class="text-xs text-brand-muted">Downloads catalog</p>
        </x-admin.card>

        <x-admin.card class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-brand-muted">Open tickets</p>
                <x-admin.badge type="danger">Attention</x-admin.badge>
            </div>
            <p class="text-3xl font-semibold text-brand-ink">{{ number_format($openTickets) }}</p>
            <p class="text-xs text-brand-muted">Customer support</p>
        </x-admin.card>
    </div>

    <x-admin.card>
        <div class="flex items-center justify-between">
            <h2 class=" font-semibold text-brand-muted">Recent tickets</h2>
            <a href="{{ route('admin.tickets.index') }}" class="text-xs font-semibold text-brand-blue hover:text-brand-blue/80">View all</a>
        </div>
        <div class="mt-4 overflow-hidden rounded-2xl border border-brand-border">
            <table class="w-full text-left ">
                <thead class="bg-brand-bg text-[10px] text-brand-muted">
                    <tr>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border bg-white">
                    @forelse($recentTickets as $ticket)
                        <tr class="odd:bg-white even:bg-brand-bg/50">
                            <td class="px-4 py-3 font-semibold text-brand-ink">{{ $ticket->reference_code }}</td>
                            <td class="px-4 py-3 text-brand-muted">{{ $ticket->subject }}</td>
                            <td class="px-4 py-3">
                                @php $statusType = $ticket->status === 'open' ? 'danger' : 'success'; @endphp
                                <x-admin.badge type="{{ $statusType }}">{{ ucfirst($ticket->status) }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3 text-xs text-brand-muted">{{ $ticket->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center  text-brand-muted">No recent tickets.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>
@endsection
