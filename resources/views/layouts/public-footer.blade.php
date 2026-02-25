@php
    use Illuminate\Support\Str;

    $siteSettings = $site_settings ?? [];
    $branding = $siteSettings['site.branding'] ?? [];
    $contact = $siteSettings['site.contact'] ?? [];
    $footer = $siteSettings['site.footer'] ?? [];
    $brandName = $branding['site_name_' . app()->getLocale()] ?? config('app.name', 'Laravel');
    $address = $contact['address_' . app()->getLocale()] ?? __('public.footer.address');
    $phone = $contact['phone'] ?? __('public.footer.phone');
    $email = $contact['email'] ?? __('public.footer.email');
    $officeHours = $contact['working_hours_' . app()->getLocale()] ?? __('public.footer.office_hours_value');
    $footerLinks = $footer['quick_links'] ?? [];

    // Footer meta links (privacy, terms, accessibility, sitemap) - allow multiple fallback keys
    $footerMeta = $footer['links'] ?? $siteSettings['site.links'] ?? [];
    $privacyUrl = $footerMeta['privacy_policy_url'] ?? $footerMeta['privacy_policy'] ?? $footer['privacy_policy_url'] ?? $siteSettings['privacy_policy_url'] ?? route('privacy');
    $termsUrl = $footerMeta['terms_service_url'] ?? $footerMeta['terms_url'] ?? $footerMeta['terms_of_service_url'] ?? $footer['terms_service_url'] ?? $siteSettings['terms_service_url'] ?? '#';
    $accessibilityUrl = $footerMeta['accessibility_url'] ?? $footerMeta['accessibility'] ?? $footer['accessibility_url'] ?? $siteSettings['accessibility_url'] ?? '#';
    
    // Social media links (you might want to add these to your settings)
  $socialLinks = [
    'facebook'  => $footerMeta['facebook_url']  ?? $footerMeta['facebook']  ?? 'https://web.facebook.com/profile.php?id=100067771711638',
    'tiktok'    => $footerMeta['tiktok_url']    ?? $footerMeta['tiktok']    ?? 'https://www.tiktok.com/@pshrdb',
    'telegram'  => $footerMeta['telegram_url']  ?? $footerMeta['telegram']  ?? 'https://t.me/aac_pshrdb',
    'linkedin'  => $footerMeta['linkedin_url']  ?? $footerMeta['linkedin']  ?? '#',
    'twitter'   => $footerMeta['twitter_url']   ?? $footerMeta['twitter']   ?? '#',
];

@endphp

<footer class="relative overflow-hidden bg-gradient-to-b from-brand-ink via-brand-ink to-gray-900 text-white">
    {{-- Animated background elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s"></div>
        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 50px 50px;"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-4 lg:gap-8">
            {{-- Brand Column --}}
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    {{-- Logo/Brand --}}
                    <div class="flex items-center space-x-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-brand-gold to-amber-500 shadow-lg transform hover:scale-105 transition-transform duration-300">
                        <x-heroicon-o-building-office-2 class="h-6 w-6 text-white" aria-hidden="true" />
                    </div>
                        <h2 class="text-xl font-bold text-white">{{ $brandName }}</h2>
                    </div>

                    <p class="max-w-md text-sm leading-relaxed text-gray-300">
                        {{ $address }}
                    </p>

                    {{-- Contact Info with Icons --}}
                    <div class="space-y-4">
                        <a href="tel:{{ $phone }}" class="group flex items-center space-x-3 text-gray-300 hover:text-orange-600 transition-colors duration-300">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 backdrop-blur-sm group-hover:bg-white/10 transition-all duration-300">
                                <x-heroicon-o-phone class="h-5 w-5" aria-hidden="true" />
                            </div>
                            <span class="text-sm">{{ $phone }}</span>
                        </a>

                        <a href="mailto:{{ $email }}" class="group flex items-center space-x-3 text-gray-300 hover:text-orange-600 transition-colors duration-300">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 backdrop-blur-sm group-hover:bg-white/10 transition-all duration-300">
                                <x-heroicon-o-envelope class="h-5 w-5" aria-hidden="true" />
                            </div>
                            <span class="text-sm">{{ $email }}</span>
                        </a>
                    </div>

                    @if(isset($visitorCount))
                        @php
                            $visitorLabel = \Illuminate\Support\Facades\Lang::has('public.footer.visitor_count')
                                ? __('public.footer.visitor_count', ['count' => number_format($visitorCount)])
                                : number_format($visitorCount) . ' visitors';
                        @endphp
                        <div class="mt-4 text-sm font-semibold text-gray-300">
                            {{ $visitorLabel }}
                        </div>
                    @endif

                 {{-- Social Media Links --}}
<div class="pt-4">
    <h4 class="mb-4 text-sm font-semibold uppercase tracking-wider text-white/90">{{ __('public.footer.follow_us') }}</h4>
    @php
        $socialIcons = [
            'facebook' => '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>',
            'tiktok' => '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
            'telegram' => '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.665 3.717l-17.73 6.837c-1.21.486-1.203 1.161-.222 1.462l4.552 1.42 10.532-6.645c.498-.303.953-.14.579.192l-8.533 7.701h-.002l.002.001-.314 4.692c.46 0 .663-.211.921-.46l2.211-2.15 4.599 3.397c.848.467 1.457.227 1.668-.785l3.019-14.228c.309-1.239-.473-1.8-1.282-1.434z"/></svg>',
            'linkedin' => '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
            'twitter' => '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>',
            'instagram' => '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/></svg>',
            'youtube' => '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 01-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 01-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 011.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418zM15.194 12 10 15V9l5.194 3z" clip-rule="evenodd"/></svg>',
        ];
    @endphp
    <div class="flex space-x-3">
        @foreach($socialLinks as $platform => $url)
            @if(isset($socialIcons[$platform]) && !empty($url))
                <a href="{{ $url }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="group relative flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 backdrop-blur-sm hover:bg-gradient-to-br hover:from-brand-gold hover:to-amber-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-brand-gold/20"
                   aria-label="{{ Str::title($platform) }}">
                    {!! $socialIcons[$platform] !!}
                </a>
            @endif
        @endforeach
    </div>
</div>
                </div>
            </div>

         {{-- Quick Links Column --}}
<div>
                <h3 class="mb-6 text-sm font-semibold uppercase tracking-wider text-white/90 after:ml-2 after:inline-block after:h-0.5 after:w-8 after:bg-gradient-to-r after:from-brand-gold after:to-amber-500">
        {{ __('public.footer.quick_links') }}
    </h3>

    <ul class="space-y-3">
        @forelse($footerLinks as $link)
            @php
                $label = $link['label_' . app()->getLocale()] ?? $link['label_en'] ?? $link['url'];
                $rawUrl = trim((string) ($link['url'] ?? ''));
                $resolvedUrl = $rawUrl;

                if ($rawUrl !== '') {
                    if (Str::startsWith($rawUrl, ['http://', 'https://', 'mailto:', 'tel:', '#', '/'])) {
                        $resolvedUrl = $rawUrl;
                    } elseif (Str::startsWith($rawUrl, '//')) {
                        $resolvedUrl = 'https:' . $rawUrl;
                    } else {
                        $resolvedUrl = 'https://' . ltrim($rawUrl, '/');
                    }
                }

                $isExternal = Str::startsWith($resolvedUrl, ['http://', 'https://', '//']);
            @endphp

            <li>
                <a href="{{ $resolvedUrl }}"
                   @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                   class="group flex items-center gap-3 rounded-lg px-2 py-1 text-sm text-gray-300 transition-all duration-300 hover:bg-white/5 hover:text-orange-600">

                    {{-- Icon --}}
                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-white/5 group-hover:bg-gradient-to-br group-hover:from-brand-gold group-hover:to-amber-500 transition-all duration-300">
                        <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4 text-gray-400 group-hover:text-white transition-colors duration-300" aria-hidden="true" />
                    </span>

                    <span class="flex-1">
                        {{ $label }}
                    </span>
                </a>
            </li>
        @empty
                        @php
                            $defaults = [
                                ['label' => __('public.navigation.services'), 'route' => 'services.index'],
                                ['label' => __('public.navigation.downloads'), 'route' => 'downloads.index'],
                                ['label' => __('public.navigation.news'), 'route' => 'news.index'],
                                ['label' => __('public.navigation.contact'), 'route' => 'contact'],
                            ];
                        @endphp

                        @foreach($defaults as $item)
                            <li>
                                <a href="{{ route($item['route']) }}"
                                   class="group flex items-center gap-3 rounded-lg px-2 py-1 text-sm text-gray-300 transition-all duration-300 hover:bg-white/5 hover:text-orange-600">

                                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-white/5 group-hover:bg-gradient-to-br group-hover:from-brand-gold group-hover:to-amber-500 transition-all duration-300">
                                        <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4 text-gray-400 group-hover:text-white transition-colors duration-300" aria-hidden="true" />
                                    </span>

                                    <span class="flex-1">
                                        {{ $item['label'] }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
        @endforelse
    </ul>
</div>


            {{-- Office Hours & Newsletter Column --}}
            <div>
                <h3 class="mb-6 text-sm font-semibold uppercase tracking-wider text-white/90 after:ml-2 after:inline-block after:h-0.5 after:w-8 after:bg-gradient-to-r after:from-brand-gold after:to-amber-500">
                    {{ __('public.footer.office_hours') }}
                </h3>
                <div class="rounded-xl bg-white/5 backdrop-blur-sm p-4 border border-white/10">
                    <div class="flex items-start space-x-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-brand-gold/20 to-amber-500/20">
                            <x-heroicon-o-clock class="h-5 w-5 text-orange-600" aria-hidden="true" />
                        </div>
                        <div>
                            <p class="text-sm leading-relaxed text-gray-300">{{ __('public.footer.office_hours_value') }}</p>
                        
                        </div>
                    </div>
                </div>

                {{-- Newsletter Subscription --}}
                <div class="mt-8">
                    <h4 class="mb-4 text-sm font-semibold uppercase tracking-wider text-white/90">{{ __('public.footer.newsletter') }}</h4>
                    <form class="space-y-3" id="newsletterForm">
                        <div class="relative">
                            <input 
                                type="email" 
                                placeholder="{{ __('public.footer.email_placeholder') }}"
                                class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-gray-400 backdrop-blur-sm focus:border-brand-gold focus:outline-none focus:ring-2 focus:ring-brand-gold/20 transition-all duration-300"
                                required
                            />
                            <button 
                                type="submit"
                                class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md bg-gradient-to-r from-brand-gold to-amber-500 px-4 py-2 text-sm font-medium text-white hover:from-amber-500 hover:to-brand-gold transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center gap-2 justify-center"
                            >
                                <span>{{ __('public.buttons.subscribe') }}</span>
                                <x-heroicon-o-arrow-up-right class="h-4 w-4" aria-hidden="true" />
                            </button>
                        </div>
                        <p class="text-xs text-gray-400">{{ __('public.footer.newsletter_note') }}</p>
                    </form>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="mt-12 border-t border-white/10 pt-8">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <div class="text-center md:text-left">
                      <p class="mt-1 text-xs text-gray-500">
                        {{ __('public.footer.disclaimer') }}
                    </p>
                    <p class="text-sm text-gray-400">
                        &copy; {{ date('Y') }} {{ $brandName }}. {{ __('public.footer.rights_reserved') }}
                    </p>
                    <p class="text-sm text-gray-400">
                    Powered By PSHRDB  ICT Directorate.
                    </p>

                  
                </div>
                
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-400">
                    <a href="{{ $privacyUrl }}" class="hover:text-orange-600 transition-colors duration-300">
                        {{ __('public.footer.privacy_policy') }}
                    </a>
                    <a href="{{ $termsUrl }}" class="hover:text-orange-600 transition-colors duration-300">
                        {{ __('public.footer.terms_service') }}
                    </a>
                    <a href="{{ $accessibilityUrl }}" class="hover:text-orange-600 transition-colors duration-300">
                        {{ __('public.footer.accessibility') }}
                    </a>
                    <a href="{{ route('sitemap.page') }}" class="hover:text-orange-600 transition-colors duration-300">
                        {{ __('public.footer.sitemap') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Back to Top Button --}}
        <button
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="fixed bottom-6 right-6 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-brand-gold to-amber-500 text-white shadow-lg transition-all duration-300 transform hover:scale-110 hover:shadow-xl hover:shadow-brand-gold/30 active:scale-95"
            aria-label="{{ __('public.buttons.back_to_top') }}"
            id="backToTop"
            style="opacity: 0; transform: translateY(20px);"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </button>
    </div>
</footer>

{{-- Add these styles to your main CSS or in a style tag --}}
<style>
    @keyframes pulse-slow {
        0%, 100% {
            opacity: 0.1;
        }
        50% {
            opacity: 0.2;
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 4s ease-in-out infinite;
    }

    /* Smooth transitions for footer elements */
    footer a, footer button {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Hover effects for interactive elements */
    .hover-lift:hover {
        transform: translateY(-2px);
    }

    /* Gradient text effect */
    .gradient-text {
        background: linear-gradient(to right, #D4AF37, #FFD700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>

<script>
        // Newsletter form submission
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        const button = newsletterForm.querySelector('button[type="submit"]');
        const statusEl = document.createElement('p');
        statusEl.className = 'text-xs text-green-400 transition-opacity duration-200 mt-1';
        newsletterForm.appendChild(statusEl);

        newsletterForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            if (! email) {
                statusEl.textContent = 'Please enter an email address.';
                statusEl.classList.replace('text-green-400', 'text-red-400');
                return;
            }

            const originalText = button.textContent;
            button.textContent = 'Subscribing...';
            button.disabled = true;
            statusEl.textContent = '';

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
            try {
                const response = await fetch(@json(route('newsletter.subscribe')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email }),
                });
                const payload = await response.json();

                if (response.ok) {
                    statusEl.textContent = payload.message ?? 'Subscribed successfully.';
                    statusEl.classList.replace('text-red-400', 'text-green-400');
                    this.reset();
                } else {
                    statusEl.textContent = payload.message ?? 'Unable to subscribe at the moment.';
                    statusEl.classList.replace('text-green-400', 'text-red-400');
                }
            } catch (error) {
                statusEl.textContent = 'Unable to subscribe at the moment.';
                statusEl.classList.replace('text-green-400', 'text-red-400');
            } finally {
                button.textContent = originalText;
                button.disabled = false;
            }
        });
    }
// Back to top button visibility
    window.addEventListener('scroll', function() {
        const backToTop = document.getElementById('backToTop');
        if (window.scrollY > 300) {
            backToTop.style.opacity = '1';
            backToTop.style.transform = 'translateY(0)';
        } else {
            backToTop.style.opacity = '0';
            backToTop.style.transform = 'translateY(20px)';
        }
    });

    // Add smooth reveal animation for footer elements
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        // Observe all footer columns
        document.querySelectorAll('footer > div > div > div').forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            observer.observe(el);
        });
    });

    // Add hover sound effect (optional)
    document.querySelectorAll('footer a, footer button').forEach(el => {
        el.addEventListener('mouseenter', function() {
            this.classList.add('hover-lift');
        });
        
        el.addEventListener('mouseleave', function() {
            this.classList.remove('hover-lift');
        });
    });
</script>
