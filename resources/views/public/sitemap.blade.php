@extends('layouts.public')

@php
    $pageTitle = $seoMeta['title'] ?? __('public.sitemap_page.title');
    $pageDescription = $seoMeta['description'] ?? __('public.sitemap_page.description');
@endphp

@push('styles')
<style>
    .sitemap-link-group {
        position: relative;
        overflow: hidden;
    }
    
    .sitemap-link-group::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, var(--color-brand-blue), var(--color-brand-gold));
        border-radius: 0 2px 2px 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .sitemap-link-group:hover::before {
        opacity: 1;
    }
    
    .sitemap-section {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .sitemap-section:hover {
        transform: translateY(-4px);
    }
    
    .link-counter {
        font-feature-settings: "tnum";
        font-variant-numeric: tabular-nums;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .stagger-delay-1 { animation-delay: 0.1s; }
    .stagger-delay-2 { animation-delay: 0.2s; }
    .stagger-delay-3 { animation-delay: 0.3s; }
    .stagger-delay-4 { animation-delay: 0.4s; }
    .stagger-delay-5 { animation-delay: 0.5s; }
</style>
@endpush

@section('content')
    <!-- Skip Navigation -->
    <a href="#sitemap-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-white focus:px-4 focus:py-2 focus:rounded-lg focus:shadow-lg focus:text-brand-blue">
        {{ __('public.common.skip_to_content') }}
    </a>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50/50 py-5 md:py-5">
        <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-gradient-to-r from-brand-blue/10 to-brand-gold/10 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-gradient-to-r from-brand-gold/10 to-blue-100/20 blur-3xl"></div>
        
        <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8" id="sitemap-content">
            <div class="text-center">
            
                
                <!-- Title -->
                <h1 class="mt-6 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 bg-clip-text text-4xl font-bold text-transparent sm:text-5xl lg:text-6xl">
                    {{ $pageTitle }}
                </h1>
          
                @if(!empty($sections))
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                        <div class="rounded-xl bg-white/80 p-3 shadow-sm ring-1 ring-slate-200/60 backdrop-blur-sm">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">
                                    <span class="link-counter">{{ count($sections) }}</span>
                                    {{ __('public.sitemap_page.stats.sections') }}
                                </span>
                            </div>
                        </div>
                        
                        @php
                            $totalLinks = 0;
                            foreach($sections as $section) {
                                if(!empty($section['links'])) $totalLinks += count($section['links']);
                                if(!empty($section['groups'])) {
                                    foreach($section['groups'] as $group) {
                                        $totalLinks += count($group['links']);
                                    }
                                }
                            }
                        @endphp
                        
                        <div class="rounded-xl bg-white/80 p-3 shadow-sm ring-1 ring-slate-200/60 backdrop-blur-sm">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">
                                    <span class="link-counter">{{ $totalLinks }}</span>
                                    {{ __('public.sitemap_page.stats.links') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Sitemap Content -->
    <section class="py-16 md:py-20" aria-label="{{ __('public.sitemap_page.aria_site_navigation') }}">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            @if(empty($sections))
                <!-- Empty State -->
                <div class="mx-auto max-w-md text-center">
                    <div class="rounded-3xl bg-gradient-to-br from-white to-slate-50 p-10 shadow-xl shadow-slate-200/40 ring-1 ring-slate-200">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-r from-blue-100/80 to-amber-100/80">
                            <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">
                            {{ __('public.sitemap_page.empty.title') }}
                        </h3>
                        <p class="mt-2 text-sm text-slate-600">
                            {{ __('public.sitemap_page.empty.description') }}
                        </p>
                    </div>
                </div>
            @else
           

                <!-- Sitemap Grid -->
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($sections as $index => $section)
                        <article 
                            class="sitemap-section group animate-fade-in-up stagger-delay-{{ min($index, 5) }} relative overflow-hidden rounded-3xl bg-gradient-to-br from-white to-slate-50/50 p-7 shadow-lg shadow-slate-200/20 ring-1 ring-slate-200/50 transition-all duration-300 hover:shadow-xl hover:shadow-brand-blue/5 hover:ring-brand-blue/20"
                            data-section="{{ strtolower(str_replace(' ', '-', $section['title'])) }}"
                        >
                            <!-- Section Header -->
                            <header class="mb-6">
                                <div class="mb-4 flex items-start justify-between">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-brand-blue/10 to-brand-gold/10">
                                        @if(!empty($section['icon']))
                                            {!! $section['icon'] !!}
                                        @else
                                            <svg class="h-6 w-6 text-brand-blue" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                    @if(!empty($section['links']))
                                        <span class="link-counter inline-flex items-center justify-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                            {{ count($section['links']) }}
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-xl font-bold text-slate-900">
                                    {{ $section['title'] }}
                                </h2>
                                @if(!empty($section['description']))
                                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                        {{ $section['description'] }}
                                    </p>
                                @endif
                            </header>

                            <!-- Links List -->
                            @if(!empty($section['links']))
                                <ul class="space-y-3">
                                    @foreach($section['links'] as $link)
                                        <li>
                                            <a
                                                href="{{ $link['url'] }}"
                                                class="group/link flex items-center justify-between gap-3 rounded-xl p-3 text-sm text-slate-700 transition-all duration-200 hover:bg-white hover:shadow-sm hover:ring-1 hover:ring-slate-200"
                                                data-searchable="{{ $link['label'] }}"
                                            >
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 group-hover/link:bg-brand-blue/10">
                                                        <svg class="h-4 w-4 text-slate-400 group-hover/link:text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                        </svg>
                                                    </div>
                                                    <span class="font-medium group-hover/link:text-brand-blue">
                                                        {{ $link['label'] }}
                                                    </span>
                                                </div>
                                                <svg class="h-4 w-4 shrink-0 text-slate-300 group-hover/link:translate-x-1 group-hover/link:text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <!-- Groups -->
                            @if(!empty($section['groups']))
                                <div class="mt-7 space-y-4">
                                    @foreach($section['groups'] as $group)
                                        <div class="sitemap-link-group relative rounded-2xl border border-slate-100 bg-gradient-to-r from-white to-slate-50/30 p-5">
                                            <div class="mb-3 flex items-center justify-between">
                                                <p class="font-semibold text-slate-900">
                                                    {{ $group['title'] }}
                                                </p>
                                                <span class="link-counter text-xs font-medium text-slate-500">
                                                    {{ count($group['links']) }}
                                                </span>
                                            </div>
                                            <ul class="space-y-2.5">
                                                @foreach($group['links'] as $link)
                                                    <li>
                                                        <a
                                                            href="{{ $link['url'] }}"
                                                            class="group/item flex items-center gap-3 text-sm text-slate-600 transition-colors hover:text-brand-blue"
                                                            data-searchable="{{ $link['label'] }}"
                                                        >
                                                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                            </svg>
                                                            <span class="group-hover/item:font-medium">
                                                                {{ $link['label'] }}
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- View All Button (if applicable) -->
                            @if(!empty($section['view_all_url']))
                                <div class="mt-6 pt-5 border-t border-slate-100">
                                    <a 
                                        href="{{ $section['view_all_url'] }}" 
                                        class="inline-flex items-center gap-2 text-sm font-medium text-brand-blue hover:text-brand-blue/80"
                                    >
                                        {{ __('public.sitemap_page.view_all') }}
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>

                <!-- Back to Top -->
                <div class="mt-12 flex justify-center">
                    <a 
                        href="#sitemap-content" 
                        class="group inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-medium text-slate-700 shadow-lg shadow-slate-200/40 ring-1 ring-slate-200 transition-all hover:bg-slate-50 hover:shadow-xl hover:ring-brand-blue/30"
                    >
                        <svg class="h-4 w-4 text-brand-blue transition-transform group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        {{ __('public.sitemap_page.back_to_top') }}
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('sitemap-search');
        const searchResults = document.getElementById('search-results');
        const resultsList = document.getElementById('results-list');
        const resultsCount = document.getElementById('results-count');
        const clearSearch = document.getElementById('clear-search');
        const sections = document.querySelectorAll('.sitemap-section');
        
        if (!searchInput) return;
        
        // Collect all links for searching
        const allLinks = [];
        sections.forEach(section => {
            const sectionTitle = section.querySelector('h2').textContent;
            const links = section.querySelectorAll('a[data-searchable]');
            
            links.forEach(link => {
                allLinks.push({
                    element: link,
                    text: link.textContent.trim(),
                    url: link.href,
                    section: sectionTitle,
                    sectionElement: section
                });
            });
        });
        
        // Search functionality
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            
            if (searchTerm.length < 2) {
                searchResults.classList.add('hidden');
                sections.forEach(section => {
                    section.classList.remove('hidden');
                    section.style.opacity = '1';
                });
                return;
            }
            
            const filteredLinks = allLinks.filter(link => 
                link.text.toLowerCase().includes(searchTerm) || 
                link.section.toLowerCase().includes(searchTerm)
            );
            
            // Update sections visibility
            sections.forEach(section => {
                const sectionLinks = Array.from(section.querySelectorAll('a[data-searchable]'));
                const hasMatch = sectionLinks.some(link => 
                    link.textContent.toLowerCase().includes(searchTerm)
                );
                
                if (hasMatch) {
                    section.classList.remove('hidden');
                    section.style.opacity = '1';
                } else {
                    section.classList.add('hidden');
                    section.style.opacity = '0';
                }
            });
            
            // Show search results panel
            if (filteredLinks.length > 0) {
                resultsList.innerHTML = '';
                filteredLinks.forEach(link => {
                    const li = document.createElement('div');
                    li.className = 'rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200/60';
                    li.innerHTML = `
                        <a href="${link.url}" class="group block">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-slate-900 group-hover:text-brand-blue">${link.text}</p>
                                    <p class="mt-1 text-xs text-slate-500">${link.section}</p>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-slate-300 group-hover:translate-x-1 group-hover:text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </a>
                    `;
                    resultsList.appendChild(li);
                });
                
                resultsCount.textContent = `Found ${filteredLinks.length} result${filteredLinks.length !== 1 ? 's' : ''}`;
                searchResults.classList.remove('hidden');
            } else {
                resultsList.innerHTML = `
                    <div class="rounded-xl bg-gradient-to-br from-amber-50/50 to-white p-6 text-center ring-1 ring-amber-200/30">
                        <svg class="mx-auto h-10 w-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-3 text-sm font-medium text-slate-900">No results found</p>
                        <p class="mt-1 text-xs text-slate-500">Try a different search term</p>
                    </div>
                `;
                resultsCount.textContent = 'No results found';
                searchResults.classList.remove('hidden');
            }
        });
        
        // Clear search
        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                searchResults.classList.add('hidden');
                sections.forEach(section => {
                    section.classList.remove('hidden');
                    section.style.opacity = '1';
                });
                searchInput.focus();
            });
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
            
            if (e.key === 'Escape' && searchInput.value) {
                searchInput.value = '';
                searchResults.classList.add('hidden');
                sections.forEach(section => {
                    section.classList.remove('hidden');
                    section.style.opacity = '1';
                });
            }
        });
        
        // Focus search on slash
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && !e.ctrlKey && !e.metaKey) {
                if (document.activeElement !== searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                }
            }
        });
        
        // Animate counters
        const counters = document.querySelectorAll('.link-counter');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent);
            let current = 0;
            const increment = target / 50;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.ceil(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target.toLocaleString();
                }
            };
            
            // Start animation when section is in viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            observer.observe(counter.closest('.sitemap-section'));
        });
    });
</script>
@endpush