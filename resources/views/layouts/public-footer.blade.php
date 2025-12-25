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
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
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
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <span class="text-sm">{{ $phone }}</span>
                        </a>

                        <a href="mailto:{{ $email }}" class="group flex items-center space-x-3 text-gray-300 hover:text-orange-600 transition-colors duration-300">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 backdrop-blur-sm group-hover:bg-white/10 transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-sm">{{ $email }}</span>
                        </a>
                    </div>

                    {{-- Social Media Links --}}
                    <div class="pt-4">
                        <h4 class="mb-4 text-sm font-semibold uppercase tracking-wider text-white/90">{{ __('common.footer.follow_us') }}</h4>
                        <div class="flex space-x-3">
                            @foreach($socialLinks as $platform => $url)
                                <a href="{{ $url }}" 
                                   class="group relative flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 backdrop-blur-sm hover:bg-gradient-to-br hover:from-brand-gold hover:to-amber-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-brand-gold/20">
                                    <svg class="h-5 w-5 text-gray-400 group-hover:text-white transition-colors duration-300">
                                        @if($platform === 'facebook')
                                            <path fill="currentColor" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        @elseif($platform === 'twitter')
                                            <path fill="currentColor" d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.213c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        @elseif($platform === 'linkedin')
                                            <path fill="currentColor" d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        @elseif($platform === 'instagram')
                                            <path fill="currentColor" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        @endif
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

         {{-- Quick Links Column --}}
<div>
    <h3 class="mb-6 text-sm font-semibold uppercase tracking-wider text-white/90 after:ml-2 after:inline-block after:h-0.5 after:w-8 after:bg-gradient-to-r after:from-brand-gold after:to-amber-500">
        {{ __('common.gov.quick_links') }}
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
                        <svg class="h-4 w-4 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </span>

                    <span class="flex-1">
                        {{ $label }}
                    </span>
                </a>
            </li>
        @empty
            @php
                $defaults = [
                    ['label' => __('common.nav.services'), 'route' => 'services.index'],
                    ['label' => __('common.nav.downloads'), 'route' => 'downloads.index'],
                    ['label' => __('common.nav.news'), 'route' => 'news.index'],
                    ['label' => __('common.nav.contact'), 'route' => 'contact.create'],
                ];
            @endphp

            @foreach($defaults as $item)
                <li>
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 rounded-lg px-2 py-1 text-sm text-gray-300 transition-all duration-300 hover:bg-white/5 hover:text-orange-600">

                        <span class="flex h-7 w-7 items-center justify-center rounded-md bg-white/5 group-hover:bg-gradient-to-br group-hover:from-brand-gold group-hover:to-amber-500 transition-all duration-300">
                            <svg class="h-4 w-4 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5l7 7-7 7" />
                            </svg>
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
                    {{ __('common.gov.office_hours') }}
                </h3>
                <div class="rounded-xl bg-white/5 backdrop-blur-sm p-4 border border-white/10">
                    <div class="flex items-start space-x-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-brand-gold/20 to-amber-500/20">
                            <svg class="h-5 w-5 text-orange-600 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm leading-relaxed text-gray-300">{{ __('common.gov.office_hours_value') }}</p>
                        
                        </div>
                    </div>
                </div>

                {{-- Newsletter Subscription --}}
                <div class="mt-8">
                    <h4 class="mb-4 text-sm font-semibold uppercase tracking-wider text-white/90">{{ __('common.footer.newsletter') }}</h4>
                    <form class="space-y-3" id="newsletterForm">
                        <div class="relative">
                            <input 
                                type="email" 
                                placeholder="{{ __('common.footer.email_placeholder') }}"
                                class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-gray-400 backdrop-blur-sm focus:border-brand-gold focus:outline-none focus:ring-2 focus:ring-brand-gold/20 transition-all duration-300"
                                required
                            />
                            <button 
                                type="submit"
                                class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md bg-gradient-to-r from-brand-gold to-amber-500 px-4 py-2 text-sm font-medium text-white hover:from-amber-500 hover:to-brand-gold transition-all duration-300 transform hover:scale-105 active:scale-95"
                            >
                                {{ __('common.actions.subscribe') }}
                            </button>
                        </div>
                        <p class="text-xs text-gray-400">{{ __('common.footer.newsletter_note') }}</p>
                    </form>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="mt-12 border-t border-white/10 pt-8">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <div class="text-center md:text-left">
                    <p class="text-sm text-gray-400">
                        &copy; {{ date('Y') }} {{ $brandName }}. {{ __('common.footer.rights_reserved') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('common.footer.disclaimer') }}
                    </p>
                </div>
                
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-400">
                    <a href="{{ $privacyUrl }}" class="hover:text-orange-600 transition-colors duration-300">
                        {{ __('common.footer.privacy_policy') }}
                    </a>
                    <a href="{{ $termsUrl }}" class="hover:text-orange-600 transition-colors duration-300">
                        {{ __('common.footer.terms_service') }}
                    </a>
                    <a href="{{ $accessibilityUrl }}" class="hover:text-orange-600 transition-colors duration-300">
                        {{ __('common.footer.accessibility') }}
                    </a>
                    <a href="{{ $sitemapUrl }}" class="hover:text-orange-600 transition-colors duration-300">
                        {{ __('common.footer.sitemap') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Back to Top Button --}}
        <button
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="fixed bottom-6 right-6 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-brand-gold to-amber-500 text-white shadow-lg transition-all duration-300 transform hover:scale-110 hover:shadow-xl hover:shadow-brand-gold/30 active:scale-95"
            aria-label="{{ __('common.actions.back_to_top') }}"
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
    document.getElementById('newsletterForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        
        // Add loading animation
        const button = this.querySelector('button[type="submit"]');
        const originalText = button.textContent;
        button.textContent = 'Subscribing...';
        button.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            // Show success message
            button.textContent = '✓ Subscribed!';
            button.classList.remove('from-brand-gold', 'to-amber-500');
            button.classList.add('from-green-500', 'to-emerald-500');
            
            // Reset after 2 seconds
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
                button.classList.remove('from-green-500', 'to-emerald-500');
                button.classList.add('from-brand-gold', 'to-amber-500');
                this.reset();
            }, 2000);
        }, 1000);
    });

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
