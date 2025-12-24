<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.nav.appointments') }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ $officeHoursService->summary() }}
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2">
                @forelse ($services as $service)
                    <a
                        href="{{ route('appointments.show', $service) }}"
                        class="block rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-gray-300"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">{{ $service->display_name }}</h3>
                        <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                            {{ $service->display_description }}
                        </p>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-gray-400">
                            {{ __('common.labels.appointment_service_duration') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $service->duration_minutes }} {{ __('common.labels.appointment_service_duration') }}
                        </p>
                    </a>
                @empty
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">
                        {{ __('common.messages.no_appointments') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
