<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookAppointmentRequest;
use App\Http\Requests\CancelAppointmentRequest;
use App\Http\Requests\TrackAppointmentRequest;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Services\AppointmentNotifier;
use App\Services\OfficeHoursService;
use App\Services\PublicCacheService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(OfficeHoursService $officeHoursService)
    {
        $services = AppointmentService::active()->orderBy('sort_order')->get();

        return view('appointments.index', compact('services', 'officeHoursService'));
    }

    public function show(AppointmentService $appointmentService, OfficeHoursService $officeHoursService)
    {
        $slots = $appointmentService->slots()
            ->active()
            ->future()
            ->withCount(['appointments as booked_count' => function ($query) {
                $query->whereIn('status', ['booked', 'confirmed']);
            }])
            ->orderBy('starts_at')
            ->get();

        return view('appointments.show', compact('appointmentService', 'slots', 'officeHoursService'));
    }

    public function book(BookAppointmentRequest $request, AppointmentService $appointmentService, OfficeHoursService $officeHoursService)
    {
        if (! $officeHoursService->isOpen()) {
            return back()->with('error', __('common.messages.office_hours_required', [
                'hours' => $officeHoursService->summary(),
            ]));
        }

        $slot = $appointmentService
            ->slots()
            ->active()
            ->where('id', $request->appointment_slot_id)
            ->firstOrFail();

        $booked = $slot->appointments()
            ->whereIn('status', ['booked', 'confirmed'])
            ->count();

        if ($booked >= $slot->capacity) {
            return back()->with('error', __('common.messages.appointment_slot_full'));
        }

        $appointment = Appointment::create([
            'appointment_service_id' => $appointmentService->id,
            'appointment_slot_id' => $slot->id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'notes' => $request->notes,
        ]);

        PublicCacheService::bump('appointments');

        app(AppointmentNotifier::class)->sendBookingNotification($appointment);

        return redirect()
            ->route('appointments.track.form', ['reference_code' => $appointment->reference_code])
            ->with('success', __('common.messages.appointment_booked'));
    }

    public function trackForm(Request $request)
    {
        $reference = $request->get('reference_code');
        $appointment = $reference ? Appointment::where('reference_code', $reference)->first() : null;

        return view('appointments.track', compact('reference', 'appointment'));
    }

    public function create(Request $request)
    {
        $services = AppointmentService::active()->orderBy('sort_order')->get();
        $slug = $request->query('service');
        $selectedService = $slug ? $services->firstWhere('slug', $slug) : $services->first();
        $slots = collect();

        if ($selectedService) {
            $slots = $selectedService->slots()
                ->active()
                ->future()
                ->orderBy('starts_at')
                ->get();
        }

        return view('appointments.create', [
            'services' => $services,
            'selectedService' => $selectedService,
            'slots' => $slots,
        ]);
    }

    public function trackSubmit(TrackAppointmentRequest $request)
    {
        $reference = $request->reference_code;
        $appointment = Appointment::where('reference_code', $reference)->first();

        return view('appointments.track', compact('reference', 'appointment'));
    }

    public function cancel(CancelAppointmentRequest $request, string $reference)
    {
        $appointment = Appointment::where('reference_code', $reference)->firstOrFail();

        if ($request->reference_code !== $reference) {
            abort(403);
        }

        if (! in_array($appointment->status, ['booked', 'confirmed'], true)) {
            return back()->with('error', __('common.messages.appointment_cannot_cancel'));
        }

        $notes = $appointment->notes;

        if ($request->filled('reason')) {
            $notes = trim($notes . "\n" . __('common.labels.cancellation_reason') . ': ' . $request->reason);
        }

        $appointment->update([
            'status' => 'cancelled',
            'notes' => $notes,
        ]);

        app(AppointmentNotifier::class)->sendStatusNotification($appointment);

        PublicCacheService::bump('appointments');

        return redirect()
            ->route('appointments.track.form', ['reference_code' => $appointment->reference_code])
            ->with('success', __('common.messages.appointment_cancelled'));
    }
}
