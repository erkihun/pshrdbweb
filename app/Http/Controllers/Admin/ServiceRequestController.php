<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateServiceRequestRequest;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Notifications\ServiceRequestStatusChanged;
use App\Events\ServiceRequestStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceRequest::with('service')->latest('submitted_at');

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->get('service_id'));
        }

        if ($request->filled('from')) {
            $query->whereDate('submitted_at', '>=', $request->get('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('submitted_at', '<=', $request->get('to'));
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('reference_code', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('full_name', 'like', '%' . $search . '%');
            });
        }

        $serviceRequests = $query->paginate(15)->withQueryString();
        $services = Service::orderBy('title_en')->get();

        return view('admin.service-requests.index', compact('serviceRequests', 'services'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load('service', 'updater');

        return view('admin.service-requests.show', compact('serviceRequest'));
    }

    public function update(UpdateServiceRequestRequest $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validated();
        $statusChanged = $serviceRequest->status !== $data['status'];
        $serviceRequest->status = $data['status'];
        $serviceRequest->admin_note = $data['admin_note'] ?? null;
        $serviceRequest->updated_by = $request->user()->id;

        if ($request->hasFile('attachment')) {
            if ($serviceRequest->attachment_path) {
                Storage::disk('public')->delete($serviceRequest->attachment_path);
            }
            $serviceRequest->attachment_path = $request->file('attachment')->store('service-requests', 'public');
        }

        $serviceRequest->save();

        if ($statusChanged && $serviceRequest->email) {
            Notification::route('mail', $serviceRequest->email)
                ->notify(new ServiceRequestStatusChanged($serviceRequest));
        }

        if ($statusChanged) {
            ServiceRequestStatusUpdated::dispatch($serviceRequest);
        }

        return redirect()
            ->route('admin.service-requests.show', $serviceRequest)
            ->with('success', __('common.messages.request_updated'));
    }
}
