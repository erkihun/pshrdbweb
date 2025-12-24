<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Services\AppointmentNotifier;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $services = AppointmentService::orderBy('sort_order')->get();
        $appointments = Appointment::with(['service', 'slot'])
            ->when($request->filled('service_id'), fn ($query) => $query->where('appointment_service_id', $request->service_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('reference'), fn ($query) => $query->where('reference_code', 'like', '%'.$request->reference.'%'))
            ->when($request->filled('search'), fn ($query) => $query->where(function ($q) use ($request) {
                $term = $request->search;

                $q->where('full_name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            }))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('booked_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('booked_at', '<=', $request->date_to))
            ->orderByDesc('booked_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments', 'services'));
    }

    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    public function update(UpdateAppointmentStatusRequest $request, Appointment $appointment)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $oldStatus = $appointment->status;

        $appointment->update($data);

        AuditLogService::log('appointments.updated', 'appointment', $appointment->id, [
            'reference_code' => $appointment->reference_code,
            'status' => $appointment->status,
        ]);

        if ($oldStatus !== $appointment->status) {
            app(AppointmentNotifier::class)->sendStatusNotification($appointment);
        }

        PublicCacheService::bump('appointments');

        return redirect()
            ->route('admin.appointments.show', $appointment)
            ->with('success', __('common.messages.appointment_status_updated'));
    }
}
