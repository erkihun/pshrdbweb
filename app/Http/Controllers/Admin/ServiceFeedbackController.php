<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceFeedback;
use Illuminate\Http\Request;

class ServiceFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceFeedback::with('service')->latest('submitted_at');

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->get('service_id'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->get('rating'));
        }

        if ($request->filled('approved')) {
            $query->where('is_approved', $request->get('approved') === '1');
        }

        $feedback = $query->paginate(15)->withQueryString();
        $services = Service::orderBy('title_en')->get();

        return view('admin.service-feedback.index', compact('feedback', 'services'));
    }

    public function show(ServiceFeedback $serviceFeedback)
    {
        $serviceFeedback->load('service');

        return view('admin.service-feedback.show', compact('serviceFeedback'));
    }

    public function update(Request $request, ServiceFeedback $serviceFeedback)
    {
        $serviceFeedback->update([
            'is_approved' => $request->boolean('is_approved'),
        ]);

        return back()->with('success', __('common.messages.feedback_updated'));
    }

    public function destroy(ServiceFeedback $serviceFeedback)
    {
        $serviceFeedback->delete();

        return redirect()->route('admin.service-feedback.index')->with('success', __('common.messages.feedback_deleted'));
    }
}
