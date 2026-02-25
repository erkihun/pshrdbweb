<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentServiceRequest;
use App\Http\Requests\UpdateAppointmentServiceRequest;
use App\Models\AppointmentService;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Support\Str;

class AppointmentServiceController extends Controller
{
    public function index()
    {
        $services = AppointmentService::withCount('slots')->orderBy('sort_order')->paginate(12);

        return view('admin.appointments.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.appointments.services.create');
    }

    public function store(StoreAppointmentServiceRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['slug'] = $this->uniqueSlug($data['name_en']);

        $service = AppointmentService::create($data);

        AuditLogService::log('appointments.services.created', 'appointment_service', $service->id, [
            'name_en' => $service->name_en,
            'slug' => $service->slug,
        ]);
        AuditLogService::log($service->is_active ? 'appointments.services.activated' : 'appointments.services.deactivated', 'appointment_service', $service->id, [
            'name_en' => $service->name_en,
            'slug' => $service->slug,
        ]);

        PublicCacheService::bump('appointments');

        return redirect()
            ->route('admin.appointment-services.show', $service)
            ->with('success', __('common.messages.appointment_service_saved'));
    }

    public function show(AppointmentService $appointmentService)
    {
        $slots = $appointmentService->slots()
            ->withCount(['appointments as booked_count' => function ($query) {
                $query->whereIn('status', ['booked', 'confirmed']);
            }])
            ->orderBy('starts_at')
            ->get();

        return view('admin.appointments.services.show', compact('appointmentService', 'slots'));
    }

    public function edit(AppointmentService $appointmentService)
    {
        return view('admin.appointments.services.edit', compact('appointmentService'));
    }

    public function update(UpdateAppointmentServiceRequest $request, AppointmentService $appointmentService)
    {
        $data = $request->validated();
        $wasActive = $appointmentService->is_active;
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($appointmentService->name_en !== $data['name_en']) {
            $data['slug'] = $this->uniqueSlug($data['name_en'], $appointmentService->id);
        }

        $appointmentService->update($data);

        AuditLogService::log('appointments.services.updated', 'appointment_service', $appointmentService->id, [
            'name_en' => $appointmentService->name_en,
            'slug' => $appointmentService->slug,
        ]);

        if ($wasActive !== $appointmentService->is_active) {
            AuditLogService::log($appointmentService->is_active ? 'appointments.services.activated' : 'appointments.services.deactivated', 'appointment_service', $appointmentService->id, [
                'name_en' => $appointmentService->name_en,
                'slug' => $appointmentService->slug,
            ]);
        }

        PublicCacheService::bump('appointments');

        return redirect()
            ->route('admin.appointment-services.show', $appointmentService)
            ->with('success', __('common.messages.appointment_service_saved'));
    }

    public function destroy(AppointmentService $appointmentService)
    {
        AuditLogService::log('appointments.services.deleted', 'appointment_service', $appointmentService->id, [
            'name_en' => $appointmentService->name_en,
            'slug' => $appointmentService->slug,
        ]);

        $appointmentService->delete();

        PublicCacheService::bump('appointments');

        return redirect()
            ->route('admin.appointment-services.index')
            ->with('success', __('common.messages.appointment_service_deleted'));
    }

    private function uniqueSlug(string $name, ?string $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base !== '' ? $base : (string) Str::uuid();
        $counter = 1;

        while (
            AppointmentService::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base !== '' ? $base . '-' . $counter : (string) Str::uuid();
            $counter++;
        }

        return $slug;
    }
}
