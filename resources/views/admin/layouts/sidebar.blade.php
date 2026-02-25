@props([
    'mobile' => false,
])

@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;

    $user = Auth::user();
    $avatarUrl = $user->avatar_path ? asset('storage/' . $user->avatar_path) : null;
    $siteSettings = $site_settings ?? [];
    $branding = $siteSettings['site.branding'] ?? [];
    $brandName = $branding['site_name_' . app()->getLocale()]
        ?? config('app.name', 'Laravel');
    $logo = $branding['logo_path']
        ?? $branding['logo']
        ?? $branding['logo_light']
        ?? $branding['logo_' . app()->getLocale()]
        ?? null;

    $groups = [
        'main' => [
            'label' => __('ui.main_group'),
            'collapsible' => false,
            'items' => [
                [
                    'label' => __('ui.dashboard'),
                    'route' => 'admin.dashboard',
                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v9a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h3',
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
                    'label' => __('ui.news_announcements'),
                    'route' => 'admin.posts.index',
                    'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
                    'permission' => 'manage posts',
                    'patterns' => ['admin.posts.*'],
                ],
                [
                    'label' => __('ui.pages'),
                    'route' => 'admin.pages.index',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'permission' => 'manage pages',
                    'patterns' => ['admin.pages.*'],
                ],
                [
                    'label' => __('ui.home_slides'),
                    'route' => 'admin.home-slides.index',
                    'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'permission' => 'manage settings',
                    'patterns' => ['admin.home-slides.*'],
                ],
                [
                    'label' => __('ui.higher_official_message'),
                    'route' => 'admin.official-message.edit',
                    'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
                    'permission' => 'manage settings',
                    'patterns' => ['admin.official-message.*'],
                ],
                [
                    'label' => __('ui.media_gallery'),
                    'route' => 'admin.media.index',
                    'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
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
                    'label' => __('ui.tickets_complaints'),
                    'route' => 'admin.tickets.index',
                    'icon' => 'M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                    'permission' => 'manage tickets',
                    'patterns' => ['admin.tickets.*'],
                ],
                [
                    'label' => __('ui.live_chat'),
                    'route' => 'admin.chats.index',
                    'icon' => 'M17 8h2a2 2 0 012 2v5a2 2 0 01-2 2h-3l-4 4v-4H7a2 2 0 01-2-2v-1a2 2 0 012-2h5.586L15 3.586A2 2 0 0117 5.414V8z',
                    'permission' => 'manage chat',
                    'patterns' => ['admin.chats.*'],
                ],
                [
                    'label' => __('ui.service_requests'),
                    'route' => 'admin.service-requests.index',
                    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                    'permission' => 'manage service requests',
                    'patterns' => ['admin.service-requests.*'],
                ],
                [
                    'label' => __('ui.document_requests'),
                    'route' => 'admin.document-requests.index',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h4l4 4v10a2 2 0 01-2 2z',
                    'permission' => 'manage document requests',
                    'patterns' => ['admin.document-requests.*'],
                ],
                [
                    'label' => __('ui.document_request_types'),
                    'route' => 'admin.document-request-types.index',
                    'icon' => 'M7 7h10v2H7zm0 4h6v2H7zm0 4h10v2H7z',
                    'permission' => 'manage document requests',
                    'patterns' => ['admin.document-request-types.*'],
                ],
                [
                    'label' => __('ui.appointments'),
                    'route' => 'admin.appointments.index',
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'permission' => 'manage appointments',
                    'patterns' => ['admin.appointments.*'],
                ],
                [
                    'label' => __('common.labels.appointment_slots'),
                    'route' => 'admin.appointment-slots.index',
                    'icon' => 'M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm2 4h10v2H7zm0 4h6v2H7z',
                    'permission' => 'manage appointments',
                    'patterns' => ['admin.appointment-slots.*'],
                ],
                [
                    'label' => __('common.labels.appointment_service'),
                    'route' => 'admin.appointment-services.index',
                    'icon' => 'M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2 5h12V9H6zm0 4h7v2H6z',
                    'permission' => 'manage appointments',
                    'patterns' => ['admin.appointment-services.*'],
                ],
            ],
        ],
        'vacancies' => [
            'label' => __('ui.vacancies'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => __('ui.vacancies'),
                    'route' => 'admin.vacancies.index',
                    'icon' => 'M3 7h4v10H3z M17 7h4v10h-4z M8 4h8l-4 5z',
                    'permission' => 'manage vacancies',
                    'patterns' => [
                        'admin.vacancies.index',
                        'admin.vacancies.create',
                        'admin.vacancies.edit',
                        'admin.vacancies.show',
                    ],
                ],
                [
                    'label' => __('ui.vacancy_applications'),
                    'route' => 'admin.vacancies.applications.index',
                    'icon' => 'M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm2 4h10M7 11h10M7 15h4',
                    'permission' => 'manage vacancy applications',
                    'patterns' => ['admin.vacancies.applications.*'],
                ],
                [
                    'label' => __('ui.analytics'),
                    'route' => 'admin.vacancies.analytics',
                    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'permission' => 'manage vacancies',
                    'patterns' => ['admin.vacancies.analytics'],
                ],
            ],
        ],
        'resources' => [
            'label' => __('ui.resources_group'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => __('ui.services'),
                    'route' => 'admin.services.index',
                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                    'permission' => 'manage services',
                    'patterns' => ['admin.services.*'],
                ],
                [
                    'label' => __('ui.document_categories'),
                    'route' => 'admin.document-categories.index',
                    'icon' => 'M4 4h16v4H4V4zm0 8h16v4H4v-4z',
                    'permission' => 'manage documents',
                    'patterns' => ['admin.document-categories.*'],
                ],
                [
                    'label' => __('ui.staff'),
                    'route' => 'admin.staff.index',
                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 1.197h-3',
                    'permission' => 'manage staff',
                    'patterns' => ['admin.staff.*'],
                ],
                [
                    'label' => __('ui.documents_downloads'),
                    'route' => 'admin.documents.index',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'permission' => 'manage documents',
                    'patterns' => ['admin.documents.*'],
                ],
                [
                    'label' => __('ui.organizations'),
                    'route' => 'admin.organizations.index',
                    'icon' => 'M3 7h4v13h10V7h4V5H3z',
                    'permission' => null,
                    'patterns' => ['admin.organizations.*'],
                ],
                [
                    'label' => __('ui.tenders_procurement'),
                    'route' => 'admin.tenders.index',
                    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    'permission' => 'manage tenders',
                    'patterns' => ['admin.tenders.*'],
                ],
            ],
        ],
        'signage' => [
            'label' => __('ui.signage'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => __('ui.signage_templates'),
                    'route' => 'admin.signage.templates.index',
                    'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
                    'permission' => 'manage signage',
                    'patterns' => ['admin.signage.templates.*'],
                ],
                [
                    'label' => __('ui.signage_displays'),
                    'route' => 'admin.signage.displays.index',
                    'icon' => 'M4 4h16v4H4V4zm0 8h8v4H4v-4z',
                    'permission' => 'manage signage',
                    'patterns' => ['admin.signage.displays.*'],
                ],
            ],
        ],
        'partnerships' => [
            'label' => __('ui.partnerships'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => __('ui.partners'),
                    'route' => 'admin.partners.index',
                    'icon' => 'M12 11c1.657 0 3-1.567 3-3.5S13.657 4 12 4s-3 1.567-3 3.5S10.343 11 12 11zm0 2c-2.21 0-4 1.343-4 3v2h8v-2c0-1.657-1.79-3-4-3z',
                    'permission' => 'manage partners',
                    'patterns' => ['admin.partners.*'],
                ],
                [
                    'label' => __('ui.mous'),
                    'route' => 'admin.mous.index',
                    'icon' => 'M3 7h18M3 12h18M3 17h18',
                    'permission' => 'manage mous',
                    'patterns' => ['admin.mous.*'],
                ],
            ],
        ],
        'citizen_charter' => [
            'label' => __('ui.citizen_charter'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => __('ui.departments'),
                    'route' => 'admin.departments.index',
                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                    'permission' => 'manage staff',
                    'patterns' => ['admin.departments.*'],
                ],
                [
                    'label' => __('ui.charter_services'),
                    'route' => 'admin.charter-services.index',
                    'icon' => 'M12 5h5l-3 4h4l-3 4h5l-5 6H6z',
                    'permission' => 'manage services',
                    'patterns' => ['admin.charter-services.*'],
                ],
            ],
        ],
        'administration' => [
            'label' => __('ui.administration_group'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => __('ui.users'),
                    'route' => 'admin.users.index',
                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 1.197h-3',
                    'permission' => 'manage users',
                    'patterns' => ['admin.users.*'],
                ],
                [
                    'label' => __('ui.roles_permissions'),
                    'route' => 'admin.roles.index',
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                    'permission' => 'manage roles',
                    'patterns' => ['admin.roles.*'],
                ],
                [
                    'label' => __('ui.sms_settings'),
                    'route' => 'admin.sms-settings.edit',
                    'icon' => 'M5 8h14M7 12h10M7 16h6',
                    'permission' => 'manage sms',
                    'patterns' => ['admin.sms-settings.*'],
                ],
                [
                    'label' => __('ui.audit_logs'),
                    'route' => 'admin.audit-logs.index',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'permission' => 'view audit logs',
                    'patterns' => ['admin.audit-logs.*'],
                ],
                [
                    'label' => __('ui.system_settings'),
                    'route' => 'admin.settings.edit',
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                    'permission' => 'manage settings',
                    'patterns' => ['admin.settings.*', 'admin.homepage.*', 'admin.sms-settings.*'],
                ],
            ],
        ],
        'optional' => [
            'label' => __('ui.optional_group'),
            'collapsible' => true,
            'items' => [
                [
                    'label' => __('ui.reports_exports'),
                    'route' => 'admin.exports.index',
                    'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'permission' => 'manage exports',
                    'patterns' => ['admin.exports.*'],
                ],
                [
                    'label' => __('ui.notifications_alerts'),
                    'route' => 'admin.alerts.index',
                    'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                    'permission' => 'manage alerts',
                    'patterns' => ['admin.alerts.*'],
                ],
                [
                    'label' => __('ui.subscriptions'),
                    'route' => 'admin.subscribers.index',
                    'icon' => 'M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                    'permission' => 'manage subscribers',
                    'patterns' => ['admin.subscribers.*'],
                ],
                [
                    'label' => __('ui.analysis'),
                    'route' => 'admin.analysis.index',
                    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'permission' => 'view analytics',
                    'patterns' => ['admin.analysis.*'],
                ],
                [
                    'label' => 'Contact info',
                    'route' => 'admin.contact-info.index',
                    'icon' => 'M8 7v3h8V7m-8 4h8V8a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2h2m8-4v4h2a2 2 0 002-2V9a2 2 0 00-2-2h-2m-8 4v6a2 2 0 002 2h4a2 2 0 002-2v-6',
                    'permission' => 'manage settings',
                    'patterns' => ['admin.contact-info.*'],
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
        ? 'fixed inset-y-0 left-0 z-40 w-72 bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-2xl'
        : 'fixed left-0 top-0 h-screen w-72 flex-shrink-0 bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-lg';
@endphp

<aside class="{{ $containerClass }}" aria-label="Admin navigation" @if($mobile) x-cloak @endif>
    <div class="flex h-full flex-col">
        <!-- Fixed Header -->
        <div class="flex-shrink-0 px-6 py-4 border-b border-gray-700 bg-gray-900/50 backdrop-blur-sm">
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="flex items-center justify-center">
                        @if($logo)
                            <img
                                src="{{ asset('storage/' . ltrim($logo, '/')) }}"
                                alt="{{ $brandName }}"
                                class="h-20 w-20 object-contain"
                                loading="eager"
                            >
                        @else
                            <x-application-logo class="h-20 w-20 text-white" aria-hidden="true" />
                        @endif
                    </div>
                    <div>
                        <span class="block text-sm font-semibold uppercase tracking-wide text-white">
                            {{ $brandName }}
                        </span>
                    
                    </div>
                </div>
                @if($mobile)
                    <button
                        type="button"
                        class="text-gray-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-gray-700"
                        @click="sidebarOpen = false"
                        aria-label="Close sidebar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Scrollable Navigation Content -->
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <div class="flex flex-col gap-3">
                @foreach ($visibleGroups as $groupKey => $group)
                    @if($group['collapsible'])
                        <div class="space-y-1">
                            <button
                                id="admin-panel-{{ $groupKey }}-trigger-{{ $mobile ? 'mobile' : 'desktop' }}"
                                type="button"
                                class="group relative flex w-full items-center justify-between rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-3 text-sm font-medium transition-all duration-200 hover:bg-gray-700 hover:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 {{ $activeGroup === $groupKey ? 'bg-gray-700 border-gray-600' : '' }}"
                                data-acc-trigger
                                data-group="{{ $groupKey }}"
                                data-group-active="{{ $activeGroup === $groupKey ? 'true' : 'false' }}"
                                aria-controls="admin-panel-{{ $groupKey }}-panel-{{ $mobile ? 'mobile' : 'desktop' }}"
                                aria-expanded="{{ $activeGroup === $groupKey ? 'true' : 'false' }}"
                                data-is-active="{{ $activeGroup === $groupKey ? 'true' : 'false' }}"
                            >
                                <span class="text-gray-300">{{ $group['label'] }}</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform duration-300 text-gray-400 {{ $activeGroup === $groupKey ? 'rotate-180' : '' }}"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    data-acc-chevron
                                >
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div
                                id="admin-panel-{{ $groupKey }}-panel-{{ $mobile ? 'mobile' : 'desktop' }}"
                                data-acc-panel
                                data-group-panel="{{ $groupKey }}"
                                role="region"
                                aria-labelledby="admin-panel-{{ $groupKey }}-trigger-{{ $mobile ? 'mobile' : 'desktop' }}"
                                class="ml-2 space-y-1 overflow-hidden pl-2 border-l border-gray-700 {{ $activeGroup === $groupKey ? 'mt-3' : 'hidden' }}"
                                style="transition: all 0.3s ease-in-out;"
                            >
                                @foreach ($group['items'] as $item)
                                    @php($isActive = collect($item['patterns'])->contains(fn ($pattern) => request()->routeIs($pattern)))
                                    <a
                                        href="{{ route($item['route']) }}"
                                        class="group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 {{ $isActive ? 'bg-blue-600 text-white shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                                        aria-current="{{ $isActive ? 'page' : 'false' }}"
                                    >
                                        <div class="flex items-center justify-center w-5 h-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                            </svg>
                                        </div>
                                        <span class="flex-1">{{ $item['label'] }}</span>
                                        @if($isActive)
                                            <div class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div>
                                        @endif
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
                                    class="group relative flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 {{ $isActive ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                                    aria-current="{{ $isActive ? 'page' : 'false' }}"
                                >
                                    <div class="flex items-center justify-center w-5 h-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                        </svg>
                                    </div>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                    @if($isActive)
                                        <div class="w-2 h-2 rounded-full bg-white animate-pulse"></div>
                                    @endif
                                </a>
                            @endforeach
                        </nav>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute right-0 top-0 h-full w-1">
            <div class="absolute right-0 top-0 w-full h-16 bg-gradient-to-b from-blue-500/20 to-transparent pointer-events-none"></div>
            <div class="absolute right-0 bottom-0 w-full h-16 bg-gradient-to-t from-blue-500/20 to-transparent pointer-events-none"></div>
        </div>
    </div>
</aside>

@push('styles')
<style>
    /* Custom scrollbar for sidebar */
    aside > div > div.overflow-y-auto {
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.3) transparent;
    }

    aside > div > div.overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }

    aside > div > div.overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    aside > div > div.overflow-y-auto::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.3);
        border-radius: 2px;
    }

    aside > div > div.overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.5);
    }

    /* Smooth scrolling */
    aside > div > div.overflow-y-auto {
        scroll-behavior: smooth;
    }

    /* Hide scrollbar when not hovering */
    aside > div > div.overflow-y-auto {
        overflow-y: scroll;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
    }

    aside > div > div.overflow-y-auto::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
    }

    aside > div > div.overflow-y-auto:hover {
        scrollbar-width: thin; /* Firefox */
    }

    aside > div > div.overflow-y-auto:hover::-webkit-scrollbar {
        display: block; /* Chrome, Safari and Opera */
    }
</style>
@endpush
