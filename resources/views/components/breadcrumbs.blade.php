@props([
    'items' => null,
    'homeUrl' => url('/'),
    'homeLabel' => __('common.nav.home'),
    'skipSegments' => [],
    'variant' => 'default', // 'default', 'glass', 'gradient', 'minimal', 'full'
    'showIcons' => true,
    'separator' => 'dot', // 'slash', 'chevron', 'dot', 'arrow'
    'theme' => 'sunrise', // 'sunrise', 'ocean', 'fire', 'citrus'
])

@php
    use Illuminate\Support\Str;
    use Illuminate\Database\Eloquent\Model;

    $itemCollection = collect($items ?? []);

    // Default skip: avoid duplicate "Home" when URL contains /home
    $skipList = collect(['home'])
        ->merge($skipSegments)
        ->map(fn ($segment) => Str::of($segment)->lower()->toString())
        ->unique()
        ->values();

    $autoItems = collect();
    $accumulated = [];

    $labelOverrides = [
        'dashboard' => __('ui.dashboard'),
        'home' => __('common.nav.home'),
        'services' => __('common.nav.services'),
        'appointments' => __('common.nav.appointments'),
        'downloads' => __('common.nav.downloads'),
        'document_requests' => __('common.nav.document_requests'),
        'document-requests' => __('common.nav.document_requests'),
        'document-request-types' => __('ui.document_request_types'),
        'document-categories' => __('ui.document_categories'),
        'document_request_types' => __('ui.document_request_types'),
        'news' => __('common.nav.news'),
        'announcements' => __('common.nav.announcements'),
        'about' => __('common.nav.about'),
        'organization' => __('common.nav.organization'),
        'leadership' => __('common.nav.leadership_staff'),
        'leadership_staff' => __('common.nav.leadership_staff'),
        'pages' => __('common.nav.pages'),
        'contact' => __('common.nav.contact'),
        'profile' => __('common.nav.profile'),
        'settings' => __('common.nav.settings'),
        'staff' => __('common.nav.staff'),
        'departments' => __('common.labels.departments'),
        'users' => __('ui.users'),
        'roles' => __('ui.roles'),
        'permissions' => __('ui.roles_permissions'),
        'tenders' => __('ui.tenders'),
        'organizations' => __('ui.organizations'),
        'media' => __('ui.media'),
        'service-requests' => __('common.labels.service_requests'),
        'service_requests' => __('common.labels.service_requests'),
        'service-feedback' => __('common.labels.service_feedback'),
        'public-servants' => __('ui.public_servants'),
        'public-servants-dashboard' => __('common.nav.public_servant_dashboard'),
        'request-service' => __('common.nav.request_service'),
        'search' => __('ui.search'),
        'news-and-announcements' => __('ui.news_announcements'),
        'track' => __('common.nav.track'),
    ];

    $routeParameters = request()->route()?->parameters() ?? [];

    $buildModelLabel = function (Model $parameter) use ($routeParameters) {
        $displayName = $parameter->display_name
            ?? $parameter->title
            ?? $parameter->name
            ?? Str::headline(class_basename($parameter));

        $createdAt = $parameter->getAttribute('created_at');

        if (empty($createdAt)) {
            return $displayName;
        }

        try {
            $position = $parameter::query()
                ->where('created_at', '<=', $createdAt)
                ->count();
        } catch (\Throwable) {
            return $displayName;
        }

        return $position > 0 ? "{$displayName} #{$position}" : $displayName;
    };

    $slugResolvers = [
        'news.show' => fn ($slug) => \App\Models\Post::query()
            ->where('type', 'news')
            ->where('slug', $slug)
            ->first(),
        'announcements.show' => fn ($slug) => \App\Models\Post::query()
            ->where('type', 'announcement')
            ->where('slug', $slug)
            ->first(),
    ];

    $friendlyModelLabel = function (string $segment) use ($routeParameters, $buildModelLabel, $slugResolvers) {
        foreach ($routeParameters as $parameter) {
            if (!($parameter instanceof Model)) {
                continue;
            }

            if (Str::lower((string) $parameter->getRouteKey()) !== Str::lower($segment)) {
                continue;
            }

            return $buildModelLabel($parameter);
        }

        foreach ($slugResolvers as $routeName => $resolver) {
            if (!request()->routeIs($routeName)) {
                continue;
            }

            $resolved = $resolver($segment);
            if ($resolved instanceof Model) {
                return $buildModelLabel($resolved);
            }
        }

        return null;
    };

    foreach (request()->segments() as $segment) {
        $normalized = Str::of($segment)->lower()->toString();

        if ($skipList->contains($normalized)) {
            continue;
        }

        $accumulated[] = $segment;

        $labelKey = Str::of($normalized)->replace('-', '_')->toString();
        $label = $labelOverrides[$labelKey]
            ?? Str::headline(Str::of($segment)->replace(['-', '_'], ' ')->toString());

        $friendlyLabel = $friendlyModelLabel($segment);
        if ($friendlyLabel) {
            $label = $friendlyLabel;
        }

        $autoItems->push([
            'label' => $label,
            'url' => url(implode('/', $accumulated)),
        ]);
    }

    $trailItems = $itemCollection->isNotEmpty() ? $itemCollection : $autoItems;

    if (request()->routeIs('admin.home-slides.edit')) {
        $trailItems = collect([
            ['label' => __('ui.home_slides'), 'url' => route('admin.home-slides.index')],
            ['label' => __('ui.edit_slide'), 'url' => url()->current()],
        ]);
    }

    $breadcrumbs = collect([['label' => $homeLabel, 'url' => $homeUrl]])
        ->concat($trailItems);

    // Color themes with blue-orange combinations
    $themes = [
        'sunrise' => [ // Clean simple theme
            'primary' => '#3b82f6',
            'secondary' => '#2563eb',
            'accent' => '#1d4ed8',
            'home' => 'text-slate-500 hover:text-blue-600',
            'item' => 'text-slate-500 hover:text-slate-900 font-semibold',
            'current' => 'text-slate-900 font-semibold',
            'separator' => 'text-slate-300',
            'bg_current' => 'bg-white border border-slate-200 shadow-sm',
            'bg_hover' => 'bg-slate-100/60 border border-slate-200',
            'icon' => 'text-slate-500',
            'icon_bg' => 'bg-slate-100',
            'container' => [
                'default' => 'bg-white border border-slate-200 shadow-sm',
                'glass' => 'bg-white/80 border border-slate-200 shadow-md',
                'gradient' => 'bg-white border border-slate-200 shadow-md',
                'minimal' => 'bg-transparent shadow-none border-0',
                'full' => 'w-full bg-white border border-slate-200 shadow',
            ],
            'gradient_text' => 'text-slate-900',
        ],
        'ocean' => [ // Cool ocean theme
            'primary' => '#0ea5e9', // Sky 500
            'secondary' => '#fb923c', // Orange 400
            'accent' => '#38bdf8', // Sky 400
            'home' => 'text-sky-600 hover:text-orange-500',
            'item' => 'text-slate-600 hover:text-sky-700',
            'current' => 'text-sky-800 font-semibold',
            'separator' => 'text-sky-300',
            'bg_current' => 'bg-gradient-to-r from-sky-50 to-orange-50 border border-sky-200',
            'bg_hover' => 'bg-sky-50/60 hover:bg-gradient-to-r hover:from-sky-50 hover:to-orange-50/30',
            'icon' => 'text-sky-500 group-hover:text-orange-500',
            'icon_bg' => 'bg-gradient-to-br from-sky-100 to-orange-100',
            'container' => [
                'default' => 'bg-gradient-to-r from-white via-sky-50/20 to-orange-50/10 shadow-md ring-1 ring-sky-100/50',
                'glass' => 'bg-white/80 backdrop-blur-xl shadow-lg ring-1 ring-white/30 border border-sky-100/50',
                'gradient' => 'bg-gradient-to-r from-sky-50/40 via-white to-orange-50/40 shadow-lg ring-1 ring-sky-200/30',
                'minimal' => 'bg-transparent shadow-none ring-0',
                'full' => 'w-full bg-white/90 shadow-lg ring-1 ring-sky-100/60 border border-sky-100/50',
            ],
            'gradient_text' => 'bg-gradient-to-r from-sky-600 to-orange-500 bg-clip-text text-transparent',
        ],
        'fire' => [ // Warm fire theme
            'primary' => '#2563eb', // Blue 600
            'secondary' => '#ea580c', // Orange 600
            'accent' => '#dc2626', // Red 600
            'home' => 'text-blue-700 hover:text-orange-700',
            'item' => 'text-slate-700 hover:text-blue-800',
            'current' => 'text-orange-800 font-bold',
            'separator' => 'text-orange-300',
            'bg_current' => 'bg-gradient-to-r from-orange-100 to-red-50 border border-orange-300',
            'bg_hover' => 'bg-blue-50/70 hover:bg-gradient-to-r hover:from-blue-50 hover:to-orange-50',
            'icon' => 'text-blue-600 group-hover:text-orange-600',
            'icon_bg' => 'bg-gradient-to-br from-blue-200 to-orange-200',
            'container' => [
                'default' => 'bg-gradient-to-r from-white via-blue-50/30 to-orange-50/20 shadow-lg ring-1 ring-orange-100/50',
                'glass' => 'bg-white/90 backdrop-blur-xl shadow-lg ring-1 ring-white/40 border border-orange-100/50',
                'gradient' => 'bg-gradient-to-r from-blue-100/50 via-white to-orange-100/50 shadow-xl ring-1 ring-orange-200/50',
                'minimal' => 'bg-transparent shadow-none ring-0',
                'full' => 'w-full bg-white/90 shadow-lg ring-1 ring-orange-100/60 border border-orange-100/50',
            ],
            'gradient_text' => 'bg-gradient-to-r from-blue-700 to-orange-700 bg-clip-text text-transparent',
        ],
        'citrus' => [ // Bright citrus theme
            'primary' => '#60a5fa', // Blue 400
            'secondary' => '#fbbf24', // Amber 400
            'accent' => '#34d399', // Emerald 400
            'home' => 'text-blue-500 hover:text-amber-600',
            'item' => 'text-slate-600 hover:text-blue-600',
            'current' => 'text-amber-700 font-semibold',
            'separator' => 'text-blue-200',
            'bg_current' => 'bg-gradient-to-r from-amber-50 to-emerald-50 border border-amber-200',
            'bg_hover' => 'bg-blue-50/60 hover:bg-gradient-to-r hover:from-blue-50 hover:to-amber-50',
            'icon' => 'text-blue-400 group-hover:text-amber-500',
            'icon_bg' => 'bg-gradient-to-br from-blue-50 to-amber-100',
            'container' => [
                'default' => 'bg-gradient-to-r from-white via-blue-50/10 to-amber-50/20 shadow-md ring-1 ring-amber-100/50',
                'glass' => 'bg-white/85 backdrop-blur-xl shadow-lg ring-1 ring-white/30 border border-amber-100/50',
                'gradient' => 'bg-gradient-to-r from-blue-50/30 via-white to-amber-50/50 shadow-lg ring-1 ring-amber-200/30',
                'minimal' => 'bg-transparent shadow-none ring-0',
                'full' => 'w-full bg-white/90 shadow-lg ring-1 ring-amber-100/60 border border-amber-100/50',
            ],
            'gradient_text' => 'bg-gradient-to-r from-blue-500 to-amber-500 bg-clip-text text-transparent',
        ],
    ];

    $theme = $themes[$theme] ?? $themes['sunrise'];
    $containerClass = $theme['container'][$variant] ?? $theme['container']['default'];

    // Separator icons with color
    $separators = [
        'slash' => '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>',
        'chevron' => '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
        'dot' => '<span class="h-1.5 w-1.5 rounded-full bg-current"></span>',
        'arrow' => '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>',
        'spark' => '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
    ];

    $separatorIcon = $separators[$separator] ?? $separators['chevron'];
@endphp

@if($trailItems->isNotEmpty())
<div {{ $attributes->merge(['class' => "relative mx-auto w-full max-w-full lg:max-w-screen-2xl px-4 sm:px-6 lg:px-10"]) }}>
    <nav aria-label="Breadcrumb" class="w-full">
    <ul
        class="flex flex-wrap items-center gap-2 rounded-2xl px-4 py-2 mt-6 mb-3
               text-[11px] uppercase ]
               transition-all duration-300 ease-out
               sm:gap-3 sm:text-[11px]
               {{ $containerClass }}"
        role="list"
    >
        @foreach($breadcrumbs as $item)
            <li class="flex items-center gap-2.5 sm:gap-3">
                @if($loop->first)
                    @if($showIcons)
                        <a
                            href="{{ $item['url'] }}"
                            class="group relative flex items-center gap-2.5 transition-all duration-300 hover:scale-105 hover:-translate-y-0.5"
                            aria-label="{{ $item['label'] }}"
                        >
                            <span class="relative">
                                <span class="absolute inset-0 {{ $theme['icon_bg'] }} rounded-lg blur-sm opacity-60 group-hover:opacity-80 transition-opacity duration-300"></span>
                                <svg
                                    class="relative h-5 w-5 shrink-0 {{ $theme['icon'] }} transition-all duration-300 group-hover:rotate-12 group-hover:scale-110 sm:h-5.5 sm:w-5.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M3 10.5L12 4l9 6.5v9a1 1 0 01-1 1h-6v-6h-4v6H4a1 1 0 01-1-1v-9z" />
                                </svg>
                            </span>
                            <span class="sr-only sm:not-sr-only {{ $theme['home'] }} transition-all duration-300 group-hover:underline group-hover:-wider">
                                {{ $item['label'] }}
                            </span>
                            <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-current to-transparent opacity-0 group-hover:opacity-30 transition-opacity duration-300"></span>
                        </a>
                    @else
                        <a
                            href="{{ $item['url'] }}"
                            class="{{ $theme['home'] }} transition-all duration-300 hover:underline hover:scale-105 relative group"
                        >
                            {{ $item['label'] }}
                            <span class="absolute -bottom-1 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-current to-transparent opacity-0 group-hover:opacity-30 transition-opacity duration-300"></span>
                        </a>
                    @endif
                @else
                    <span 
                        class="{{ $theme['separator'] }} flex items-center transition-all duration-300 hover:scale-110 hover:rotate-6" 
                        aria-hidden="true"
                    >
                        {!! $separatorIcon !!}
                    </span>
                    
                    @if($loop->last)
                        <a
                            href="{{ $item['url'] }}"
                            class="whitespace-nowrap {{ $theme['current'] }} px-3.5 py-2 rounded-lg {{ $theme['bg_current'] }} 
                                   shadow-sm relative overflow-hidden group"
                            aria-current="page"
                        >
                            <span class="relative z-10 flex items-center gap-2">
                                @if($variant === 'glass' || $variant === 'gradient')
                                <span class="h-2 w-2 rounded-full bg-current opacity-70 animate-pulse"></span>
                                @endif
                                {{ $item['label'] }}
                            </span>
                            <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
                        </a>
                    @else
                        <a
                            href="{{ $item['url'] }}"
                            class="whitespace-nowrap {{ $theme['item'] }} 
                                   transition-all duration-300 hover:underline hover:scale-[1.02] 
                                   px-3 py-1.5 rounded-lg {{ $theme['bg_hover'] }}
                                   border border-transparent hover:border-blue-200/50
                                   relative group overflow-hidden"
                        >
                            <span class="relative z-10">{{ $item['label'] }}</span>
                            <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></span>
                        </a>
                    @endif
                @endif
            </li>
        @endforeach
        
        @if($variant === 'gradient' || $variant === 'glass')
        <li class="ml-auto hidden sm:flex items-center gap-2.5">
            <div class="h-6 w-px bg-gradient-to-b from-transparent via-current/30 to-transparent"></div>
            <div class="flex items-center gap-1.5">
                <div class="h-2 w-2 rounded-full {{ $theme['gradient_text'] }} bg-clip-border"></div>
                <span class="text-xs {{ $theme['item'] }} font-normal normal-case -normal">
                    {{ count($breadcrumbs) - 1 }} {{ Str::plural('level', count($breadcrumbs) - 1) }}
                </span>
            </div>
        </li>
        @endif
    </ul>
    </nav>
</div>
@endif

@push('styles')
<style>
    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }
    
    .breadcrumb-shimmer {
        animation: shimmer 2s infinite;
    }
    
    .animate-glow {
        animation: glow 2s ease-in-out infinite alternate;
    }
    
    @keyframes glow {
        from {
            box-shadow: 0 0 5px rgba(59, 130, 246, 0.3), 0 0 10px rgba(249, 115, 22, 0.2);
        }
        to {
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5), 0 0 20px rgba(249, 115, 22, 0.3);
        }
    }
</style>
@endpush

{{-- Optional: Add micro-animation for breadcrumb changes --}}
@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const breadcrumb = document.querySelector('[aria-label="Breadcrumb"]');
        if (breadcrumb) {
            // Entrance animation
            breadcrumb.style.opacity = '0';
            breadcrumb.style.transform = 'translateY(-10px) scale(0.98)';
            
            setTimeout(() => {
                breadcrumb.style.transition = 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                breadcrumb.style.opacity = '1';
                breadcrumb.style.transform = 'translateY(0) scale(1)';
            }, 150);
            
            // Add hover effects to all links
            const breadcrumbLinks = breadcrumb.querySelectorAll('a');
            breadcrumbLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'transform 0.2s ease-out';
                });
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Add click ripple effect
            breadcrumbLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.7);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        top: ${y}px;
                        left: ${x}px;
                        pointer-events: none;
                        z-index: 1;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        }
    });
    
    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
@endonce
