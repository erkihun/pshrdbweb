<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequestRequest;
use App\Http\Requests\TrackDocumentRequestRequest;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestType;
use App\Services\DocumentRequestNotifier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentRequestController extends Controller
{
    public function index()
    {
        $types = DocumentRequestType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('document-requests.index', compact('types'));
    }

    public function show(string $slug)
    {
        $type = DocumentRequestType::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('document-requests.show', compact('type'));
    }

    public function store(StoreDocumentRequestRequest $request, string $slug, DocumentRequestNotifier $notifier)
    {
        $type = DocumentRequestType::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $data = $request->validated();

        $reference = 'DR-' . Str::upper(Str::random(8));

        $documentRequest = DocumentRequest::create([
            'reference_code' => $reference,
            'document_request_type_id' => $type->id,
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'id_number' => $data['id_number'] ?? null,
            'address_am' => $data['address_am'] ?? null,
            'address_en' => $data['address_en'] ?? null,
            'purpose' => $data['purpose'] ?? null,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        if ($request->hasFile('attachment')) {
            $documentRequest->attachment_path = $request->file('attachment')->store('document-requests', 'public');
            $documentRequest->save();
        }

        $notifier->notifySubmission($documentRequest);

        return redirect()
            ->route('document-requests.track.form', ['reference_code' => $reference])
            ->with('success', __('common.messages.document_request_submitted'));
    }

    public function trackForm()
    {
        $reference = request('reference_code');
        $documentRequest = $reference
            ? DocumentRequest::where('reference_code', $reference)->first()
            : null;

        return view('document-requests.track', compact('documentRequest', 'reference'));
    }

    public function trackSubmit(TrackDocumentRequestRequest $request)
    {
        $reference = $request->input('reference_code');
        $documentRequest = DocumentRequest::where('reference_code', $reference)->first();

        return view('document-requests.track', compact('documentRequest', 'reference'));
    }
}
