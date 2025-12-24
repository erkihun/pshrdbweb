@php
    $siteSettings = $site_settings ?? [];
    $branding = $siteSettings['site.branding'] ?? [];
    $contact = $siteSettings['site.contact'] ?? [];
    $footer = $siteSettings['site.footer'] ?? [];
    $brandName = $branding['site_name_' . app()->getLocale()] ?? config('app.name', 'Laravel');
    $address = $contact['address_' . app()->getLocale()] ?? __('common.gov.address');
    $phone = $contact['phone'] ?? __('common.gov.phone');
    $email = $contact['email'] ?? __('common.gov.email');
    $officeHours = $contact['working_hours_' . app()->getLocale()] ?? __('common.gov.office_hours_value');
    $footerLinks = $footer['quick_links'] ?? [];
@endphp

<footer class="bg-brand-ink text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div>
            <div class="text-sm font-semibold uppercase tracking-[0.3em] text-white">{{ $brandName }}</div>
            <p class="mt-3 text-sm text-white/80">{{ $address }}</p>
            <p class="mt-2 text-sm text-white/80">{{ $phone }}</p>
            <p class="mt-2 text-sm text-white/80">{{ $email }}</p>
        </div>
        <div>
            <div class="text-sm font-semibold uppercase tracking-[0.3em] text-white">{{ __('common.gov.quick_links') }}</div>
            <div class="mt-3 flex flex-col gap-2 text-sm text-white/80">
                @forelse($footerLinks as $link)
                    @php
                        $label = $link['label_' . app()->getLocale()] ?? $link['label_en'] ?? $link['url'];
                    @endphp
                    <a href="{{ $link['url'] }}" class="hover:text-brand-gold">{{ $label }}</a>
                @empty
                    <a href="{{ route('services.index') }}" class="hover:text-brand-gold">{{ __('common.nav.services') }}</a>
                    <a href="{{ route('downloads.index') }}" class="hover:text-brand-gold">{{ __('common.nav.downloads') }}</a>
                    <a href="{{ route('news.index') }}" class="hover:text-brand-gold">{{ __('common.nav.news') }}</a>
                    <a href="{{ route('contact.create') }}" class="hover:text-brand-gold">{{ __('common.nav.contact') }}</a>
                @endforelse
            </div>
        </div>
        <div>
            <div class="text-sm font-semibold uppercase tracking-[0.3em] text-white">{{ __('common.gov.office_hours') }}</div>
            <p class="mt-3 text-sm text-white/80">{{ $officeHours }}</p>
        </div>
    </div>
</footer>
