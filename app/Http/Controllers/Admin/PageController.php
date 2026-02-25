<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::whereIn('key', $this->keys())
            ->get()
            ->keyBy('key');

        $items = collect($this->keys())->map(function ($key) use ($pages) {
            return [
                'key' => $key,
                'page' => $pages->get($key),
            ];
        });

        $missingKeys = collect($this->keys())
            ->reject(fn ($key) => $pages->has($key))
            ->values();

        return view('admin.pages.index', compact('items', 'missingKeys'));
    }

    public function edit(string $key)
    {
        if (! in_array($key, $this->keys(), true)) {
            abort(404);
        }

        $page = Page::firstWhere('key', $key);

        return view('admin.pages.edit', compact('page', 'key'));
    }

    public function create()
    {
        $pages = Page::whereIn('key', $this->keys())
            ->get()
            ->keyBy('key');

        $availableKeys = collect($this->keys())
            ->reject(fn ($key) => $pages->has($key))
            ->values();

        if ($availableKeys->isEmpty()) {
            return redirect()
                ->route('admin.pages.index')
                ->with('error', 'All pages are already defined.');
        }

        return view('admin.pages.create', compact('availableKeys'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'in:'.implode(',', $this->keys())],
            'title_am' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'body_am' => ['required', 'string'],
            'body_en' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        foreach (['body_am', 'body_en'] as $field) {
            if (! empty($data[$field])) {
                $data[$field] = $this->sanitizeRichText($data[$field]);
            }
        }

        $data['is_published'] = $request->boolean('is_published');
        $data['updated_by'] = $request->user()->id;

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $this->storeCover($request->file('cover_image'), null);
        }

        // TinyMCE returns HTML for body fields; consider sanitizing before rendering in public-facing views.
        Page::updateOrCreate(['key' => $data['key']], $data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function update(Request $request, string $key)
    {
        if (! in_array($key, $this->keys(), true)) {
            abort(404);
        }

        $data = $request->validate([
            'title_am' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'body_am' => ['required', 'string'],
            'body_en' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        foreach (['body_am', 'body_en'] as $field) {
            if (! empty($data[$field])) {
                $data[$field] = $this->sanitizeRichText($data[$field]);
            }
        }

        $page = Page::firstWhere('key', $key);
        $data['is_published'] = $request->boolean('is_published');
        $data['updated_by'] = $request->user()->id;
        $data['key'] = $key;

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $this->storeCover($request->file('cover_image'), $page?->cover_image_path);
        } else {
            $data['cover_image_path'] = $page?->cover_image_path;
        }

        // TinyMCE returns HTML for body fields; sanitize when exposing to untrusted visitors.
        Page::updateOrCreate(['key' => $key], $data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    private function keys(): array
    {
        return [
            'about',
            'mission_vision_values',
            'leadership',
            'structure',
            'history',
        ];
    }

    private function sanitizeRichText(string $html): string
    {
        $withoutScript = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);

        $withoutListeners = preg_replace_callback('/<([a-z]+)([^>]*)>/i', function ($matches) {
            $cleanAttributes = preg_replace('/\s+on[a-z]+=(["\']).*?\\1/i', '', $matches[2]);
            $cleanAttributes = preg_replace('/\s+on[a-z]+=[^\\s>]+/i', '', $cleanAttributes);
            return "<{$matches[1]}{$cleanAttributes}>";
        }, $withoutScript);

        return preg_replace('/javascript:/i', '', $withoutListeners);
    }

    private function storeCover($file, ?string $existingPath): string
    {
        if ($existingPath) {
            Storage::disk('public')->delete($existingPath);
        }

        return $file->store('pages', 'public');
    }
}
