<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

final class MediaController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            return $this->handlePost($request);
        }

        return view('admin.media.index', [
            'media' => Media::latest()->paginate(24),
        ]);
    }

    private function handlePost(Request $request): RedirectResponse
    {
        $action = (string) $request->input('_action', 'store');

        return match ($action) {
            'store' => $this->store($request),
            'update' => $this->update($request),
            'delete' => $this->delete($request),
            default => back()->with('error', 'Invalid action.'),
        };
    }

    private function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            '_action' => ['required', Rule::in(['store'])],
            'title' => ['nullable', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:20480', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip'],
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        Media::create([
            'title' => $validated['title'] ?? null,
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => (string) $file->getMimeType(),
            'size' => (int) $file->getSize(),
        ]);

        return back()->with('success', 'Media uploaded.');
    }

    private function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            '_action' => ['required', Rule::in(['update'])],
            'id' => ['required', 'uuid'],
            'title' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip'],
        ]);

        /** @var Media $medium */
        $medium = Media::query()->findOrFail($validated['id']);

        $data = [
            'title' => $validated['title'] ?? null,
        ];

        if ($request->hasFile('file')) {
            Storage::disk($medium->disk)->delete($medium->path);

            $file = $request->file('file');
            $path = $file->store('media', 'public');

            $data = array_merge($data, [
                'disk' => 'public',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => (string) $file->getMimeType(),
                'size' => (int) $file->getSize(),
            ]);
        }

        $medium->update($data);

        return back()->with('success', 'Media updated.');
    }

    private function delete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            '_action' => ['required', Rule::in(['delete'])],
            'id' => ['required', 'uuid'],
        ]);

        /** @var Media $medium */
        $medium = Media::query()->findOrFail($validated['id']);

        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return back()->with('success', 'Media deleted.');
    }
}
