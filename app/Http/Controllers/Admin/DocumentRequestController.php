<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDocumentRequestStatusRequest;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestType;
use App\Services\DocumentRequestNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentRequest::with('type')->latest('submitted_at');

        if ($request->filled('type_id')) {
            $query->where('document_request_type_id', $request->get('type_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('from')) {
            $query->whereDate('submitted_at', '>=', $request->get('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('submitted_at', '<=', $request->get('to'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('reference_code', 'like', '%' . $search . '%')
                    ->orWhere('full_name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $documentRequests = $query->paginate(15)->withQueryString();
        $types = DocumentRequestType::orderBy('sort_order')->get();

        return view('admin.document-requests.index', compact('documentRequests', 'types'));
    }

    public function show(DocumentRequest $documentRequest)
    {
        $documentRequest->load('type', 'updatedBy');

        return view('admin.document-requests.show', compact('documentRequest'));
    }

    public function update(UpdateDocumentRequestStatusRequest $request, DocumentRequest $documentRequest, DocumentRequestNotifier $notifier)
    {
        $was = $documentRequest->status;

        $documentRequest->update([
            'status' => $request->input('status'),
            'admin_note' => $request->input('admin_note'),
            'updated_by' => $request->user()->id,
        ]);

        if ($was !== $documentRequest->status) {
            $notifier->notifyStatus($documentRequest);
        }

        return back()->with('success', __('common.messages.document_request_updated'));
    }

    public function download(DocumentRequest $documentRequest)
    {
        if (! $documentRequest->attachment_path) {
            abort(404);
        }

        return Storage::disk('public')->download($documentRequest->attachment_path);
    }
}
