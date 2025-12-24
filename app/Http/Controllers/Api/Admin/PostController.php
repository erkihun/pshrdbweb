<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->filled('status')) {
            if ($request->get('status') === 'published') {
                $query->where('is_published', true);
            } elseif ($request->get('status') === 'draft') {
                $query->where('is_published', false);
            }
        }

        return PostResource::collection($query->paginate(15));
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['title'] = $data['title_en'];
        $data['body'] = $data['body_en'];
        $data['excerpt'] = $data['excerpt_en'] ?? null;
        $data['slug'] = $this->uniqueSlug($data['title_en']);
        $data['is_published'] = $request->boolean('is_published');
        if (! $data['is_published']) {
            $data['published_at'] = null;
        }

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

        return new PostResource($post);
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $wasPublished = $post->is_published;
        $data['is_published'] = $request->boolean('is_published');
        if (! $data['is_published']) {
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

        return new PostResource($post);
    }

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

        return response()->json(['message' => 'Deleted']);
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
}
