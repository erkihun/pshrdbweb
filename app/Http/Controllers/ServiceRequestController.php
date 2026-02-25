<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequestRequest;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceRequestController extends Controller
{
    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        return view('service-requests.create', compact('services'));
    }

    public function store(StoreServiceRequestRequest $request)
    {
        $data = $request->validated();
        $data['reference_code'] = $this->generateReference();
        $data['status'] = 'submitted';
        $data['submitted_at'] = now();

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('service-requests', 'public');
        }

        $serviceRequest = ServiceRequest::create($data);

        return redirect()
            ->route('service-requests.track.form', ['reference_code' => $serviceRequest->reference_code])
            ->with('success', __('common.messages.request_submitted'));
    }

    public function trackForm()
    {
        $reference = request()->get('reference_code');
        $serviceRequest = $reference
            ? ServiceRequest::where('reference_code', $reference)->first()
            : null;

        return view('service-requests.track', compact('serviceRequest', 'reference'));
    }

    public function trackSubmit()
    {
        $reference = request()->validate([
            'reference_code' => ['required', 'string'],
        ])['reference_code'];

        $serviceRequest = ServiceRequest::where('reference_code', $reference)->first();

        return view('service-requests.track', [
            'serviceRequest' => $serviceRequest,
            'reference' => $reference,
        ]);
    }

    private function generateReference(): string
    {
        do {
            $code = 'SR-' . Str::upper(Str::random(8));
        } while (ServiceRequest::where('reference_code', $code)->exists());

        return $code;
    }
}
