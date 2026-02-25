<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::with('category')
            ->latest()
            ->paginate(10);

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DocumentCategory::orderBy('sort_order')->get();

        return view('admin.documents.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['title'] = $data['title_en'];
        $data['description'] = $data['description_en'] ?? null;
        $data['slug'] = $this->uniqueSlug($data['title_en']);
        if (! $data['is_published']) {
            $data['published_at'] = null;
        }

        $file = $request->file('file');
        $data['file_path'] = $file->store('documents', 'public');
        $data['file_type'] = strtolower($file->getClientOriginalExtension());
        $data['file_size'] = $file->getSize();

        $document = Document::create($data);

        AuditLogService::log('documents.uploaded', 'document', $document->id, [
            'title' => $document->title,
            'slug' => $document->slug,
            'file_type' => $document->file_type,
        ]);

        PublicCacheService::bump('downloads');

        return redirect()
            ->route('admin.documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->load('category');

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $categories = DocumentCategory::orderBy('sort_order')->get();

        return view('admin.documents.edit', compact('document', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        if (! $data['is_published']) {
            $data['published_at'] = null;
        }

        $data['title'] = $data['title_en'];
        $data['description'] = $data['description_en'] ?? null;

        if ($document->title_en !== $data['title_en']) {
            $data['slug'] = $this->uniqueSlug($data['title_en'], $document->id);
        }

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $data['file_path'] = $file->store('documents', 'public');
            $data['file_type'] = strtolower($file->getClientOriginalExtension());
            $data['file_size'] = $file->getSize();
        }

        $document->update($data);

        AuditLogService::log('documents.updated', 'document', $document->id, [
            'title' => $document->title,
            'slug' => $document->slug,
            'file_type' => $document->file_type,
        ]);

        PublicCacheService::bump('downloads');

        return redirect()
            ->route('admin.documents.show', $document)
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        AuditLogService::log('documents.deleted', 'document', $document->id, [
            'title' => $document->title,
            'slug' => $document->slug,
            'file_type' => $document->file_type,
        ]);

        $document->delete();

        PublicCacheService::bump('downloads');

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    private function uniqueSlug(string $title, ?string $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base !== '' ? $base : (string) Str::uuid();
        $counter = 1;

        while (
            Document::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base !== '' ? $base . '-' . $counter : (string) Str::uuid();
            $counter++;
        }

        return $slug;
    }
}
