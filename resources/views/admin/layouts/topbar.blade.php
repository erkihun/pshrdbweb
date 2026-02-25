@php
    $locale = app()->getLocale();
    $role = Auth::user()->getRoleNames()->first();
    $user = Auth::user();
    $avatarUrl = $user->avatar_path ? asset('storage/' . $user->avatar_path) : null;
@endphp

<header class="fixed top-0 left-72 right-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur-lg supports-[backdrop-filter]:bg-white/80">
    <div class="mx-auto flex items-center gap-4 px-6 py-3">
        {{-- Mobile Menu Button --}}
        <button
            type="button"
            class="inline-flex items-center justify-center rounded-xl border border-transparent bg-white p-2 text-slate-500 shadow-sm ring-1 ring-slate-200 transition-all hover:border-slate-300 hover:text-slate-900 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-blue lg:hidden"
            @click="sidebarOpen = true"
            aria-label="Open sidebar"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Page Title --}}
        <div class="flex-1">
            <div class="flex items-center gap-3">
                <h1 class="text-lg font-semibold text-slate-900">
                    @if(isset($title))
                        {{ $title }}
                    @else
                        @yield('title', __('ui.dashboard'))
                    @endif
                </h1>
            </div>
        </div>

        {{-- Header Actions --}}
        <div class="flex items-center gap-3">
            {{-- Simple Bell Icon (no dropdown) --}}
            <button
                type="button"
                class="relative flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white shadow-sm ring-1 ring-slate-200/50 transition-all hover:border-slate-200 hover:bg-slate-50 hover:text-slate-900 hover:shadow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-blue"
                aria-label="Notifications"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>

            {{-- Language Switcher --}}
            <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm ring-1 ring-slate-200/50">
                <form method="POST" action="{{ route('locale.switch', 'am') }}" class="leading-none">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                    <button 
                        type="submit" 
                        class="rounded-full px-2 py-0.5 transition-all {{ $locale === 'am' ? 'bg-gradient-to-r from-brand-blue to-blue-600 text-white font-bold shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        {{ __('ui.am') }}
                    </button>
                </form>
                <span class="text-slate-300">/</span>
                <form method="POST" action="{{ route('locale.switch', 'en') }}" class="leading-none">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                    <button 
                        type="submit" 
                        class="rounded-full px-2 py-0.5 transition-all {{ $locale === 'en' ? 'bg-gradient-to-r from-brand-blue to-blue-600 text-white font-bold shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        {{ __('ui.en') }}
                    </button>
                </form>
            </div>

            {{-- Profile Dropdown --}}
            <div class="relative">
                <button
                    type="button"
                    class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-1.5 shadow-sm ring-1 ring-slate-200/50 transition-all hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 hover:shadow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-blue"
                    data-dropdown-trigger="profile"
                    aria-expanded="false"
                >
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }} avatar" class="h-9 w-9 rounded-full object-cover shadow-sm ring-1 ring-slate-200">
                    @else
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-brand-blue to-blue-600 font-semibold uppercase text-white shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden text-left leading-tight sm:block">
                        <p class="text-[10px] font-medium text-slate-400">{{ __('ui.profile') }}</p>
                        <p class="text-sm font-semibold text-slate-900 truncate max-w-[120px]">{{ $user->name }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-3.5 w-3.5 text-slate-400 sm:block" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div
                    data-dropdown-panel="profile"
                    role="menu"
                    aria-hidden="true"
                    class="hidden absolute right-0 z-20 mt-3 w-56 rounded-2xl border border-slate-200 bg-white p-4 shadow-lg"
                >
                    <p class="text-[10px] text-slate-400">{{ __('ui.profile') }}</p>
                    <a href="{{ route('admin.profile') }}" class="mt-2 block rounded-xl px-3 py-2 font-semibold text-slate-600 hover:bg-slate-50">{{ __('ui.profile') }}</a>
                    @if($role)
                        <p class="mt-1 text-[10px] text-slate-500">{{ $role }}</p>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mt-1 w-full rounded-xl px-3 py-2 text-left font-semibold text-slate-600 hover:bg-slate-50">{{ __('ui.logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Add this after the header to push content down --}}
<div style="height: 64px;"></div>

@push('scripts')
<script>
    // Simple dropdown functionality (like original)
    document.addEventListener('DOMContentLoaded', function() {
        const triggers = document.querySelectorAll('[data-dropdown-trigger]');
        
        triggers.forEach(trigger => {
            const panelId = trigger.getAttribute('data-dropdown-trigger');
            const panel = document.querySelector(`[data-dropdown-panel="${panelId}"]`);
            
            if (!panel) return;
            
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = panel.classList.contains('hidden');
                
                // Close all other panels
                document.querySelectorAll('[data-dropdown-panel]').forEach(p => {
                    p.classList.add('hidden');
                    p.setAttribute('aria-hidden', 'true');
                });
                
                // Update all triggers
                document.querySelectorAll('[data-dropdown-trigger]').forEach(t => {
                    t.setAttribute('aria-expanded', 'false');
                });
                
                // Toggle current panel
                if (isHidden) {
                    panel.classList.remove('hidden');
                    panel.setAttribute('aria-hidden', 'false');
                    trigger.setAttribute('aria-expanded', 'true');
                } else {
                    panel.classList.add('hidden');
                    panel.setAttribute('aria-hidden', 'true');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('[data-dropdown-panel]').forEach(panel => {
                panel.classList.add('hidden');
                panel.setAttribute('aria-hidden', 'true');
            });
            document.querySelectorAll('[data-dropdown-trigger]').forEach(trigger => {
                trigger.setAttribute('aria-expanded', 'false');
            });
        });
        
        // Prevent closing when clicking inside dropdown
        document.querySelectorAll('[data-dropdown-panel]').forEach(panel => {
            panel.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>
@endpush
