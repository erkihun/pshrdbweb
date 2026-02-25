<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentSlotRequest;
use App\Http\Requests\UpdateAppointmentSlotRequest;
use App\Models\AppointmentService;
use App\Models\AppointmentSlot;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Http\Request;

class AppointmentSlotController extends Controller
{
    public function index(Request $request)
    {
        $services = AppointmentService::orderBy('sort_order')->get();
        $slots = AppointmentSlot::with('service')
            ->when($request->filled('service_id'), fn ($query) => $query->where('appointment_service_id', $request->service_id))
            ->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.appointments.slots.index', compact('slots', 'services'));
    }

    public function create()
    {
        $services = AppointmentService::orderBy('sort_order')->get();

        return view('admin.appointments.slots.create', compact('services'));
    }

    public function store(StoreAppointmentSlotRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $slot = AppointmentSlot::create($data);

        AuditLogService::log('appointments.slots.created', 'appointment_slot', $slot->id, [
            'service_id' => $slot->appointment_service_id,
            'starts_at' => $slot->starts_at->toIso8601String(),
        ]);

        PublicCacheService::bump('appointments');

        return redirect()
            ->route('admin.appointment-slots.index')
            ->with('success', __('common.messages.appointment_slot_saved'));
    }

    public function edit(AppointmentSlot $appointmentSlot)
    {
        $services = AppointmentService::orderBy('sort_order')->get();

        return view('admin.appointments.slots.edit', compact('appointmentSlot', 'services'));
    }

    public function update(UpdateAppointmentSlotRequest $request, AppointmentSlot $appointmentSlot)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $appointmentSlot->update($data);

        AuditLogService::log('appointments.slots.updated', 'appointment_slot', $appointmentSlot->id, [
            'service_id' => $appointmentSlot->appointment_service_id,
            'starts_at' => $appointmentSlot->starts_at->toIso8601String(),
        ]);

        PublicCacheService::bump('appointments');

        return redirect()
            ->route('admin.appointment-slots.index')
            ->with('success', __('common.messages.appointment_slot_saved'));
    }

    public function destroy(AppointmentSlot $appointmentSlot)
    {
        AuditLogService::log('appointments.slots.deleted', 'appointment_slot', $appointmentSlot->id, [
            'service_id' => $appointmentSlot->appointment_service_id,
            'starts_at' => $appointmentSlot->starts_at->toIso8601String(),
        ]);

        $appointmentSlot->delete();

        PublicCacheService::bump('appointments');

        return redirect()
            ->route('admin.appointment-slots.index')
            ->with('success', __('common.messages.appointment_slot_deleted'));
    }
}
