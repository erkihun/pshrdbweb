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
    $privacyUrl = $footerMeta['privacy_policy_url'] ?? $footerMeta['privacy_policy'] ?? $footer['privacy_policy_url'] ?? $siteSettings['privacy_policy_url'] ?? '#';
    $termsUrl = $footerMeta['terms_service_url'] ?? $footerMeta['terms_url'] ?? $footerMeta['terms_of_service_url'] ?? $footer['terms_service_url'] ?? $siteSettings['terms_service_url'] ?? '#';
    $accessibilityUrl = $footerMeta['accessibility_url'] ?? $footerMeta['accessibility'] ?? $footer['accessibility_url'] ?? $siteSettings['accessibility_url'] ?? '#';
    $sitemapUrl = $footerMeta['sitemap_url'] ?? $footerMeta['sitemap'] ?? $footer['sitemap_url'] ?? $siteSettings['sitemap_url'] ?? '#';
    
    // Social media links (you might want to add these to your settings)
  $socialLinks = [
    'facebook'  => $footerMeta['facebook_url']  ?? $footerMeta['facebook']  ?? '#',
    'twitter'   => $footerMeta['twitter_url']   ?? $footerMeta['twitter']   ?? '#',
    'linkedin'  => $footerMeta['linkedin_url']  ?? $footerMeta['linkedin']  ?? '#',
    'instagram' => $footerMeta['instagram_url'] ?? $footerMeta['instagram'] ?? '#',
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
                                'facebook' => 'globe-alt',
                                'twitter' => 'sparkles',
                                'linkedin' => 'link',
                                'instagram' => 'camera',
                            ];
                        @endphp
                        <div class="flex space-x-3">
                            @foreach($socialLinks as $platform => $url)
                                <a href="{{ $url }}"
                                   class="group relative flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 backdrop-blur-sm hover:bg-gradient-to-br hover:from-brand-gold hover:to-amber-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-brand-gold/20"
                                   aria-label="{{ Str::title($platform) }}">
                                    <x-dynamic-component
                                        :component="'heroicon-o-'.($socialIcons[$platform] ?? 'link')"
                                        class="h-5 w-5 text-gray-400 transition-colors duration-300 group-hover:text-white"
                                        aria-hidden="true"
                                    />
                                </a>
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
            @endphp

            <li>
                <a href="{{ $link['url'] }}"
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
                    <p class="text-sm text-gray-400">
                        &copy; {{ date('Y') }} {{ $brandName }}. {{ __('public.footer.rights_reserved') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('public.footer.disclaimer') }}
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
                    <a href="{{ $sitemapUrl }}" class="hover:text-orange-600 transition-colors duration-300">
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
