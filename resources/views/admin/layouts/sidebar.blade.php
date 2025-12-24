@props([
    'mobile' => false,
])

@php
    use Illuminate\Support\Facades\Route;

    $user = Auth::user();

    $groups = [
        'main' => [
            'label' => __('ui.main_group'),
            'collapsible' => false,
            'items' => [
                [
                    'label' => __('ui.dashboard'),
                    'route' => 'admin.dashboard',
                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0v9a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h3',
                    'permission' => 'view dashboard',
                    'patterns' => ['admin.dashboard'],
                ],
            ],
        ],
        'content' => [
            'label' => __('ui.content_group'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => 'News & Announcements',
                    'route' => 'admin.posts.index',
                    'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
                    'permission' => 'manage posts',
                    'patterns' => ['admin.posts.*'],
                ],
                [
                    'label' => 'Pages',
                    'route' => 'admin.pages.index',
                    'icon' => 'M12 20c-2.21 0-4-1.79-4-4V6c0-2.21 1.79-4 4-4s4 1.79 4 4v10c0 2.21-1.79 4-4 4zm-2-4h4v2H10v-2z',
                    'permission' => 'manage pages',
                    'patterns' => ['admin.pages.*'],
                ],
                [
                    'label' => 'Media Gallery',
                    'route' => 'admin.media.index',
                    'icon' => 'M4 4h16v6H4V4zm0 8h8v6H4v-6zm10 0h6v6h-6v-6z',
                    'permission' => 'manage media',
                    'patterns' => ['admin.media.*'],
                ],
            ],
        ],
        'operations' => [
            'label' => __('ui.operations_group'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => 'Tickets & Complaints',
                    'route' => 'admin.tickets.index',
                    'icon' => 'M5 3h14a1 1 0 011 1v16l-5-5H5a1 1 0 01-1-1V4a1 1 0 011-1z',
                    'permission' => 'manage tickets',
                    'patterns' => ['admin.tickets.*'],
                ],
                [
                    'label' => 'Service Requests',
                    'route' => 'admin.service-requests.index',
                    'icon' => 'M12 2a5 5 0 00-5 5v1H5a2 2 0 00-2 2v3h14v-3a2 2 0 00-2-2h-2V7a5 5 0 00-5-5zm-3 10v3a1 1 0 001 1h6a1 1 0 001-1v-3h-8z',
                    'permission' => 'manage service requests',
                    'patterns' => ['admin.service-requests.*'],
                ],
                [
                    'label' => 'Appointments',
                    'route' => 'admin.appointments.index',
                    'icon' => 'M7 8h10M7 12h10M7 16h4',
                    'permission' => 'manage appointments',
                    'patterns' => ['admin.appointments.*'],
                ],
            ],
        ],
        'resources' => [
            'label' => __('ui.resources_group'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => 'Services',
                    'route' => 'admin.services.index',
                    'icon' => 'M6 3h12a2 2 0 012 2v6H4V5a2 2 0 012-2zm0 8h12v6H6v-6z',
                    'permission' => 'manage services',
                    'patterns' => ['admin.services.*'],
                ],
                [
                    'label' => 'Documents & Downloads',
                    'route' => 'admin.documents.index',
                    'icon' => 'M9 2h6l5 5v13a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1h4z',
                    'permission' => 'manage documents',
                    'patterns' => ['admin.documents.*'],
                ],
                [
                    'label' => 'Tenders / Procurement',
                    'route' => 'admin.tenders.index',
                    'icon' => 'M5 4h14v3H5V4zm0 5h14v3H5V9zm0 5h14v3H5v-3z',
                    'permission' => 'manage tenders',
                    'patterns' => ['admin.tenders.*'],
                ],
            ],
        ],
        'administration' => [
            'label' => __('ui.administration_group'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => 'Users',
                    'route' => 'admin.users.index',
                    'icon' => 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z',
                    'permission' => 'manage users',
                    'patterns' => ['admin.users.*'],
                ],
                [
                    'label' => 'Roles & Permissions',
                    'route' => 'admin.roles.index',
                    'icon' => 'M9 4h6a1 1 0 011 1v2H8V5a1 1 0 011-1zm-1 5h8v2H8V9zm0 4h5v2H8v-2z',
                    'permission' => 'manage roles',
                    'patterns' => ['admin.roles.*'],
                ],
                [
                    'label' => 'Audit Logs',
                    'route' => 'admin.audit-logs.index',
                    'icon' => 'M8 7V5a4 4 0 018 0v2m1 4h-8m-4 3h6m-6-3v4h14v-4',
                    'permission' => 'view audit logs',
                    'patterns' => ['admin.audit-logs.*'],
                ],
                [
                    'label' => 'System Settings',
                    'route' => 'admin.settings.edit',
                    'icon' => 'M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4m0-8v8m0-8L9 4m3 4l3-4',
                    'permission' => 'manage settings',
                    'patterns' => ['admin.settings.*', 'admin.homepage.*', 'admin.sms-settings.*'],
                ],
            ],
        ],
        'optional' => [
            'label' => 'Optional',
            'collapsible' => true,
            'items' => [
                [
                    'label' => 'Reports & Exports',
                    'route' => 'admin.exports.index',
                    'icon' => 'M5 4h14v2H5V4zm0 5h14v2H5V9zm0 5h14v2H5v-2z',
                    'permission' => 'manage exports',
                    'patterns' => ['admin.exports.*'],
                ],
                [
                    'label' => 'Notifications / Alerts',
                    'route' => 'admin.alerts.index',
                    'icon' => 'M15 17H9l-1-2V9a4 4 0 118 0v6l-1 2zm-3 5a2 2 0 002-2H10a2 2 0 002 2z',
                    'permission' => 'manage alerts',
                    'patterns' => ['admin.alerts.*'],
                ],
                [
                    'label' => 'Subscriptions',
                    'route' => 'admin.subscribers.index',
                    'icon' => 'M6 11h12v2H6z',
                    'permission' => 'manage subscribers',
                    'patterns' => ['admin.subscribers.*'],
                ],
                [
                    'label' => 'Analytics',
                    'route' => 'admin.analytics.index',
                    'icon' => 'M3 13h3v6H3zm7-6h3v12h-3zm7-4h3v16h-3z',
                    'permission' => 'view analytics',
                    'patterns' => ['admin.analytics.*'],
                ],
            ],
        ],
    ];

    $visibleGroups = [];
    foreach ($groups as $key => $group) {
        $items = [];
        foreach ($group['items'] as $item) {
            if (empty($item['route']) || ! Route::has($item['route'])) {
                continue;
            }

            $requiresPermission = ! empty($item['permission']);
            $hasPermission = $requiresPermission ? ($user && $user->can($item['permission'])) : true;
            if (! $hasPermission) {
                continue;
            }

            $items[] = $item;
        }

        if (count($items)) {
            $group['items'] = $items;
            $visibleGroups[$key] = $group;
        }
    }

    $activeGroup = null;
    foreach ($visibleGroups as $key => $group) {
        foreach ($group['items'] as $item) {
            if (collect($item['patterns'])->contains(fn ($pattern) => request()->routeIs($pattern))) {
                $activeGroup = $key;
                break 2;
            }
        }
    }

    $activeGroup ??= array_key_first($visibleGroups);
    $containerClass = $mobile
        ? 'fixed inset-y-0 left-0 z-40 w-72 border border-brand-nav/20 bg-brand-sidebar text-brand-nav shadow-2xl'
        : 'relative flex h-full w-72 flex-shrink-0 border border-brand-nav/20 bg-brand-sidebar text-brand-nav shadow-sm';
@endphp

<aside class="{{ $containerClass }}" aria-label="Admin navigation" @if($mobile) x-cloak @endif>
    <div class="flex h-full flex-col overflow-y-auto px-6 py-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[12px]  text-white/70">{{ __('ui.language') }}</p>
                <p class=" text-white">{{ config('app.name', 'Laravel') }}</p>
            </div>
            @if($mobile)
                <button
                    type="button"
                    class="text-brand-muted hover:text-brand-blue"
                    @click="sidebarOpen = false"
                    aria-label="Close sidebar"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </div>

        <div class="mt-6 flex flex-1 flex-col gap-4">
            @foreach ($visibleGroups as $groupKey => $group)
                @if($group['collapsible'])
                    <div class="space-y-2">
                        <button
                            id="admin-panel-{{ $groupKey }}-trigger-{{ $mobile ? 'mobile' : 'desktop' }}"
                            type="button"
                            class="group relative flex w-full items-center justify-between  border border-transparent bg-transparent px-3 py-2   text-white/70 transition hover:bg-white/5 hover:text-white focus-visible:outline focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-brand-gold focus-visible:ring-offset-brand-sidebar {{ $activeGroup === $groupKey ? 'text-white' : 'text-white/70' }}"
                            data-acc-trigger
                            data-group="{{ $groupKey }}"
                            data-group-active="{{ $activeGroup === $groupKey ? 'true' : 'false' }}"
                            aria-controls="admin-panel-{{ $groupKey }}-panel-{{ $mobile ? 'mobile' : 'desktop' }}"
                            aria-expanded="{{ $activeGroup === $groupKey ? 'true' : 'false' }}"
                            data-is-active="{{ $activeGroup === $groupKey ? 'true' : 'false' }}"
                        >
                            <span class=" text-white/70">{{ $group['label'] }}</span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 transition duration-300 text-current"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                data-acc-chevron
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                            <div
                                id="admin-panel-{{ $groupKey }}-panel-{{ $mobile ? 'mobile' : 'desktop' }}"
                                data-acc-panel
                                data-group-panel="{{ $groupKey }}"
                                role="region"
                                aria-labelledby="admin-panel-{{ $groupKey }}-trigger-{{ $mobile ? 'mobile' : 'desktop' }}"
                                class="mt-3 space-y-1 overflow-hidden transition-all duration-300 ease-in-out {{ $activeGroup === $groupKey ? '' : 'hidden' }}"
                            >
                            @foreach ($group['items'] as $item)
                                    @php($isActive = collect($item['patterns'])->contains(fn ($pattern) => request()->routeIs($pattern)))
                                <a
                                    href="{{ route($item['route']) }}"
                                    class="relative flex items-center gap-3 rounded-2xl border border-transparent pl-10 pr-3 py-2   transition focus-visible:outline focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-brand-gold focus-visible:ring-offset-brand-sidebar {{ $isActive ? 'bg-[rgba(212,161,6,0.15)] text-brand-gold rounded-xl shadow-sm' : 'text-brand-nav hover:bg-white/5 hover:text-white' }}"
                                    aria-current="{{ $isActive ? 'page' : 'false' }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-current transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                                    </svg>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <nav class="space-y-1">
                        @foreach ($group['items'] as $item)
                            @php($isActive = collect($item['patterns'])->contains(fn ($pattern) => request()->routeIs($pattern)))
                            <a
                                href="{{ route($item['route']) }}"
                                class="relative flex items-center gap-3 rounded-2xl border border-transparent pl-10 pr-3 py-2   transition focus-visible:outline focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-brand-gold focus-visible:ring-offset-brand-sidebar {{ $isActive ? 'bg-[rgba(212,161,6,0.15)] text-brand-gold rounded-xl shadow-sm' : 'text-brand-nav hover:bg-white/5 hover:text-white' }}"
                                aria-current="{{ $isActive ? 'page' : 'false' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-current transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                @endif
            @endforeach
        </div>

        <div class="mt-8 border-t border-brand-border pt-5">
            <div class="  text-brand-ink">{{ $user->name }}</div>
            <p class=" text-brand-muted">{{ $user->email }}</p>
            @if($user->getRoleNames()->isNotEmpty())
            <div class="mt-2 inline-flex items-center rounded-full border border-brand-blue/20 bg-brand-blue/10 px-3 py-1 text-[10px]  text-brand-blue">
                {{ $user->getRoleNames()->first() }}
            </div>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full rounded-2xl border border-brand-border bg-brand-bg px-4 py-2   text-brand-muted transition hover:bg-white">{{ __('ui.logout') }}</button>
            </form>
        </div>
    </div>
</aside>
