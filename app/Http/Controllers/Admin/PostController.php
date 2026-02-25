<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postsQuery = Post::latest();

        if (request()->filled('type')) {
            $postsQuery->where('type', request('type'));
        }

        if (request()->filled('status')) {
            if (request('status') === 'published') {
                $postsQuery->where('is_published', true);
            } elseif (request('status') === 'draft') {
                $postsQuery->where('is_published', false);
            }
        }

        $posts = $postsQuery->paginate(10)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        foreach (['body_am', 'body_en'] as $field) {
            if (! empty($data[$field])) {
                $data[$field] = $this->sanitizeRichText($data[$field]);
            }
        }
        foreach (['body_am', 'body_en'] as $field) {
            if (! empty($data[$field])) {
                $data[$field] = $this->sanitizeRichText($data[$field]);
            }
        }
        $data['title'] = $data['title_en'];
        $data['body'] = $data['body_en'];
        $data['excerpt'] = $data['excerpt_en'] ?? null;
        $data['slug'] = $this->uniqueSlug($data['title_en']);
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published']) {
            $data['published_at'] = $data['published_at'] ?? Carbon::now();
        } else {
            $data['published_at'] = null;
        }
        $data['posted_at'] = Carbon::now();

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('posts', 'public');
        }

        $post = Post::create($data);

        AuditLogService::log('posts.created', 'post', $post->id, [
            'title' => $post->title,
            'slug' => $post->slug,
            'type' => $post->type,
        ]);

        if ($post->is_published) {
            AuditLogService::log('posts.published', 'post', $post->id, [
                'title' => $post->title,
                'slug' => $post->slug,
            ]);
        }

        PublicCacheService::bump('news');
        PublicCacheService::bump('announcement');

        return redirect()
            ->route('admin.posts.show', $post)
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $wasPublished = $post->is_published;
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published']) {
            $data['published_at'] = $post->published_at ?? Carbon::now();
        } else {
            $data['published_at'] = null;
        }

        $data['title'] = $data['title_en'];
        $data['body'] = $data['body_en'];
        $data['excerpt'] = $data['excerpt_en'] ?? null;

        if ($post->title_en !== $data['title_en']) {
            $data['slug'] = $this->uniqueSlug($data['title_en'], $post->id);
        }

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image_path) {
                Storage::disk('public')->delete($post->cover_image_path);
            }

            $data['cover_image_path'] = $request->file('cover_image')->store('posts', 'public');
        }

        $post->update($data);

        AuditLogService::log('posts.updated', 'post', $post->id, [
            'title' => $post->title,
            'slug' => $post->slug,
            'type' => $post->type,
        ]);

        if ($wasPublished !== $post->is_published) {
            AuditLogService::log($post->is_published ? 'posts.published' : 'posts.unpublished', 'post', $post->id, [
                'title' => $post->title,
                'slug' => $post->slug,
            ]);
        }

        PublicCacheService::bump('news');
        PublicCacheService::bump('announcement');

        return redirect()
            ->route('admin.posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->cover_image_path) {
            Storage::disk('public')->delete($post->cover_image_path);
        }

        AuditLogService::log('posts.deleted', 'post', $post->id, [
            'title' => $post->title,
            'slug' => $post->slug,
            'type' => $post->type,
        ]);

        $post->delete();

        PublicCacheService::bump('news');
        PublicCacheService::bump('announcement');

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    private function uniqueSlug(string $title, ?string $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base !== '' ? $base : (string) Str::uuid();
        $counter = 1;

        while (
            Post::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base !== '' ? $base . '-' . $counter : (string) Str::uuid();
            $counter++;
        }

        return $slug;
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
}
