<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $categories = DocumentCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $documentsQuery = Document::with('category')
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderBy('published_at', 'desc');

        $selectedCategory = null;
        if ($request->filled('category')) {
            $selectedCategory = DocumentCategory::where('slug', $request->get('category'))
                ->where('is_active', true)
                ->first();

            if ($selectedCategory) {
                $documentsQuery->where('document_category_id', $selectedCategory->id);
            }
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $documentsQuery->where(function ($query) use ($search) {
                $query->where('title_am', 'like', '%' . $search . '%')
                    ->orWhere('title_en', 'like', '%' . $search . '%');
            });
        }

        $cacheable = ! $request->filled('category') && ! $request->filled('q');
        if ($cacheable) {
            $locale = app()->getLocale();
            $page = (int) $request->get('page', 1);
            $cacheKey = PublicCacheService::key('downloads', $locale, $page);
            $documents = Cache::remember($cacheKey, PublicCacheService::TTL, function () use ($documentsQuery) {
                return $documentsQuery->paginate(12)->withQueryString();
            });
        } else {
            $documents = $documentsQuery->paginate(12)->withQueryString();
        }

        return view('downloads.index', compact('documents', 'categories', 'selectedCategory'));
    }

    public function show(string $slug)
    {
        $document = Document::with('category')
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where('slug', $slug)
            ->firstOrFail();

        $otherDocuments = Document::with('category')
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where('id', '!=', $document->id)
            ->orderBy('published_at', 'desc')
            ->get();

        return view('downloads.show', compact('document', 'otherDocuments'));
    }

    public function file(string $slug)
    {
        $document = Document::where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where('slug', $slug)
            ->firstOrFail();

        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        $filename = Str::slug($document->display_title);
        $filename = $filename !== '' ? $filename : $document->slug;
        $filename .= '.' . $document->file_type;

        AuditLogService::log('documents.downloaded', 'document', $document->id, [
            'title' => $document->title,
            'slug' => $document->slug,
            'file_type' => $document->file_type,
        ]);

        $downloadHitKey = 'download:hit:'.$document->id.':'.now()->format('Y-m-d');
        if (! session()->has($downloadHitKey)) {
            $document->increment('downloads_count');
            session()->put($downloadHitKey, true);
        }

        return Storage::disk('public')->download($document->file_path, $filename);
    }
}
