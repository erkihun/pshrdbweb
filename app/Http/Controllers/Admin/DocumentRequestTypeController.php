<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequestType;
use Illuminate\Http\Request;

class DocumentRequestTypeController extends Controller
{
    public function index()
    {
        $types = DocumentRequestType::orderBy('sort_order')->get();

        return view('admin.document-request-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.document-request-types.form', ['type' => new DocumentRequestType()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_am' => ['required', 'string'],
            'name_en' => ['required', 'string'],
            'slug' => ['required', 'string', 'unique:document_request_types,slug'],
            'requirements_am' => ['nullable', 'string'],
            'requirements_en' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        DocumentRequestType::create(array_merge($data, ['is_active' => $request->boolean('is_active')]));

        return redirect()->route('admin.document-request-types.index')->with('success', __('common.messages.document_request_type_saved'));
    }

    public function edit(DocumentRequestType $documentRequestType)
    {
        return view('admin.document-request-types.form', ['type' => $documentRequestType]);
    }

    public function update(Request $request, DocumentRequestType $documentRequestType)
    {
        $data = $request->validate([
            'name_am' => ['required', 'string'],
            'name_en' => ['required', 'string'],
            'slug' => ['required', 'string', 'unique:document_request_types,slug,' . $documentRequestType->id],
            'requirements_am' => ['nullable', 'string'],
            'requirements_en' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $documentRequestType->update(array_merge($data, ['is_active' => $request->boolean('is_active')]));

        return redirect()->route('admin.document-request-types.index')->with('success', __('common.messages.document_request_type_saved'));
    }

    public function destroy(DocumentRequestType $documentRequestType)
    {
        $documentRequestType->delete();

        return redirect()->route('admin.document-request-types.index')->with('success', __('common.messages.document_request_type_deleted'));
    }
}
