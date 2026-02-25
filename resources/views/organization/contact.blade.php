@extends('layouts.public')

@section('content')
@php
    use Illuminate\Support\Str;

    $primaryOrg = $organizations->first();
    $addressParts = array_filter([
        $primaryOrg->physical_address ?? null,
        $primaryOrg->city ?? null,
        $primaryOrg->region ?? null,
        $primaryOrg->country ?? null,
    ]);
    $formattedAddress = $addressParts ? implode(', ', $addressParts) : 'Addis Ababa, Ethiopia';
    $seoMeta = [
        'title' => __('common.nav.contact'),
        'description' => $primaryOrg
            ? "Contact {$primaryOrg->name} at {$formattedAddress}."
            : 'Organization contact details for Addis Ababa public service.',
        'url' => route('organization.contact'),
        'canonical' => route('organization.contact'),
    ];

    $mapQueryTemplate = fn ($organization) => trim(
        ($organization->physical_address ?? '') . ' ' .
        ($organization->city ?? '') . ' ' .
        ($organization->region ?? '') . ' ' .
        ($organization->country ?? '')
    );
@endphp

<section class="bg-gray-50 py-16">
    <div class="mx-auto w-full max-w-7xl space-y-10 px-4 sm:px-6 lg:px-8">
        <div class="space-y-4 text-left">
            <h1 class="text-3xl font-semibold text-slate-900">Organization Contacts</h1>
            <p class="text-sm text-slate-500">Select an organization to view its full contact and address details.</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,260px)_minmax(0,1fr)]">
            <aside class="space-y-4 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-gray-500">Organizations</h2>
                <div class="space-y-2">
                    @foreach($organizations as $index => $organization)
                        <button
                            type="button"
                            class="org-tab flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-white/80 px-4 py-3 text-left text-sm font-semibold text-gray-700 transition hover:border-blue-400 hover:bg-blue-50 {{ $index === 0 ? 'border-blue-500 bg-blue-50' : '' }}"
                            data-target="org-{{ $index }}"
                        >
                            <span>{{ $organization->name }}</span>
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $organization->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $organization->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </aside>

            <div class="space-y-8">
                @foreach($organizations as $index => $organization)
                    @php
                        $phones = array_filter([
                            $organization->phone_primary,
                            $organization->phone_secondary,
                        ]);

                        $emails = array_filter([
                            $organization->email_primary,
                            $organization->email_secondary,
                        ]);

                        $mapEmbedUrl = trim((string) ($organization->map_embed_url ?? ''));
                        $canEmbedMap = $mapEmbedUrl !== '' && Str::startsWith($mapEmbedUrl, 'https://www.google.com/maps/embed');

                        $mapQuery = $mapQueryTemplate($organization);
                        $mapLink = !$canEmbedMap && $mapQuery
                            ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($mapQuery)
                            : null;

                        $mapSearchEmbedUrl = $mapQuery
                            ? 'https://www.google.com/maps?q=' . urlencode($mapQuery) . '&output=embed'
                            : null;
                    @endphp

                    <article
                        id="org-{{ $index }}"
                        class="org-detail space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm"
                        @if($index !== 0) hidden @endif
                    >
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.35em] text-blue-500">{{ __('common.labels.organization') }}</p>
                                <h2 class="text-2xl font-semibold text-slate-900">{{ $organization->name }}</h2>
                                @if($organization->code)
                                    <p class="text-xs text-gray-400 uppercase tracking-[0.3em]">Code: {{ $organization->code }}</p>
                                @endif
                            </div>
                            @if($organization->website_url)
                                <a
                                    href="{{ $organization->website_url }}"
                                    target="_blank"
                                    rel="nofollow noopener"
                                    class="text-sm font-semibold text-blue-600 hover:underline"
                                >
                                    Visit website
                                </a>
                            @endif
                        </div>

                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 text-sm">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Phones</p>
                                @forelse ($phones as $phone)
                                    <a href="tel:{{ preg_replace('/\\D+/', '', $phone) }}" class="mt-3 block text-lg font-semibold text-slate-900 hover:text-blue-600">
                                        {{ $phone }}
                                    </a>
                                @empty
                                    <p class="mt-3 text-sm text-gray-400">Not available</p>
                                @endforelse
                            </div>

                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 text-sm">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Emails</p>
                                @forelse ($emails as $email)
                                    <a href="mailto:{{ $email }}" class="mt-3 block text-lg font-semibold text-slate-900 hover:text-blue-600">
                                        {{ $email }}
                                    </a>
                                @empty
                                    <p class="mt-3 text-sm text-gray-400">Not available</p>
                                @endforelse
                            </div>

                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 text-sm">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Status</p>
                                <p class="mt-3 rounded-full border border-gray-200 bg-white/70 px-3 py-1 text-xs font-semibold uppercase tracking-[0.4em] text-gray-600">
                                    {{ $organization->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Physical address</p>
                            <div class="mt-3 space-y-1 text-sm text-gray-600">
                                @if($organization->physical_address)
                                    <p class="font-semibold text-slate-900">{{ $organization->physical_address }}</p>
                                @endif
                                <p>
                                    {{ $organization->city ?? '' }}
                                    @if($organization->region)
                                        , {{ $organization->region }}
                                    @endif
                                </p>
                                <p>{{ $organization->country ?? '' }}</p>
                            </div>
                        </div>

                        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.8fr)]">
                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900">Map</h3>
                                <div class="mt-4">
                                    @if($canEmbedMap)
                                        <div class="h-64 w-full overflow-hidden rounded-2xl border border-gray-200">
                                            <iframe
                                                class="h-full w-full border-0"
                                                loading="lazy"
                                                src="{{ $mapEmbedUrl }}"
                                                title="{{ $organization->name }} location map"
                                                allowfullscreen
                                            ></iframe>
                                        </div>
                                    @elseif($mapSearchEmbedUrl)
                                        <div class="h-64 w-full overflow-hidden rounded-2xl border border-gray-200">
                                            <iframe
                                                class="h-full w-full border-0"
                                                loading="lazy"
                                                src="{{ $mapSearchEmbedUrl }}"
                                                title="{{ $organization->name }} location preview"
                                                allowfullscreen
                                            ></iframe>
                                        </div>
                                        @if($mapLink)
                                            <p class="mt-3 text-sm text-gray-500">
                                                <span>Preview based on the organization's address.</span>
                                                <a
                                                    href="{{ $mapLink }}"
                                                    target="_blank"
                                                    rel="nofollow noopener"
                                                    class="font-semibold text-blue-600 hover:underline"
                                                >
                                                    Open in Google Maps
                                                </a>
                                            </p>
                                        @endif
                                    @else
                                        <div class="flex h-64 items-center justify-center text-sm text-gray-400">
                                            Map preview not available.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900">Social links</h3>
                                <div class="mt-4 space-y-2 text-sm text-gray-700">
                                    @if($organization->website_url)
                                        <a href="{{ $organization->website_url }}" target="_blank" rel="nofollow noopener" class="block rounded-lg border border-gray-200 px-4 py-3 hover:bg-blue-50">
                                            Website
                                        </a>
                                    @endif
                                    @if($organization->facebook_url)
                                        <a href="{{ $organization->facebook_url }}" target="_blank" rel="nofollow noopener" class="block rounded-lg border border-gray-200 px-4 py-3 hover:bg-blue-50">
                                            Facebook
                                        </a>
                                    @endif
                                    @if($organization->telegram_url)
                                        <a href="{{ $organization->telegram_url }}" target="_blank" rel="nofollow noopener" class="block rounded-lg border border-gray-200 px-4 py-3 hover:bg-blue-50">
                                            Telegram
                                        </a>
                                    @endif
                                    @if($organization->twitter_url)
                                        <a href="{{ $organization->twitter_url }}" target="_blank" rel="nofollow noopener" class="block rounded-lg border border-gray-200 px-4 py-3 hover:bg-blue-50">
                                            Twitter/X
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.org-tab');
        const details = document.querySelectorAll('.org-detail');

        if (!tabs.length || !details.length) {
            return;
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                const targetId = this.dataset.target;

                details.forEach(detail => {
                    detail.hidden = detail.id !== targetId;
                });

                tabs.forEach(item => {
                    item.classList.remove('border-blue-500', 'bg-blue-50');
                });

                this.classList.add('border-blue-500', 'bg-blue-50');
            });
        });
    });
</script>
@endpush
@endsection
