<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\AuditLogService;
use App\Services\PublicCacheService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AnnouncementController extends Controller
{
    private const TYPE = 'announcement';

    public function index()
    {
        $postsQuery = Post::where('type', self::TYPE)->latest();

        if (request()->filled('status')) {
            if (request('status') === 'published') {
                $postsQuery->where('is_published', true);
            } elseif (request('status') === 'draft') {
                $postsQuery->where('is_published', false);
            }
        }

        if (request()->filled('search')) {
            $term = '%' . request('search') . '%';
            $postsQuery->where(function ($builder) use ($term) {
                $builder->where('title', 'like', $term)
                    ->orWhere('title_am', 'like', $term)
                    ->orWhere('title_en', 'like', $term);
            });
        }

        $posts = $postsQuery->paginate(10)->withQueryString();

        return view('admin.announcements.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(StorePostRequest $request)
    {
        $data = $this->preparePayload($request);
        $data['type'] = self::TYPE;

        $post = Post::create($data);

        AuditLogService::log('announcements.created', 'post', $post->id, [
            'title' => $post->title,
            'slug' => $post->slug,
        ]);

        if ($post->is_published) {
            AuditLogService::log('announcements.published', 'post', $post->id, [
                'title' => $post->title,
                'slug' => $post->slug,
            ]);
        }

        PublicCacheService::bump('announcement');

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit(Post $announcement)
    {
        $this->ensureAnnouncement($announcement);

        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(UpdatePostRequest $request, Post $announcement)
    {
        $this->ensureAnnouncement($announcement);

        $data = $this->preparePayload($request, $announcement);
        $wasPublished = $announcement->is_published;

        if ($request->boolean('is_published')) {
            $data['published_at'] = $announcement->published_at ?? Carbon::now();
        } else {
            $data['published_at'] = null;
        }

        $data['is_published'] = $request->boolean('is_published');

        if ($announcement->title_en !== $data['title_en']) {
            $data['slug'] = $this->uniqueSlug($data['title_en'], $announcement->id);
        }

        if ($request->hasFile('cover_image')) {
            if ($announcement->cover_image_path) {
                Storage::disk('public')->delete($announcement->cover_image_path);
            }

            $data['cover_image_path'] = $request->file('cover_image')->store('posts', 'public');
        }

        $announcement->update($data);

        AuditLogService::log('announcements.updated', 'post', $announcement->id, [
            'title' => $announcement->title,
            'slug' => $announcement->slug,
        ]);

        if ($wasPublished !== $announcement->is_published) {
            AuditLogService::log(
                $announcement->is_published ? 'announcements.published' : 'announcements.unpublished',
                'post',
                $announcement->id,
                [
                    'title' => $announcement->title,
                    'slug' => $announcement->slug,
                ]
            );
        }

        PublicCacheService::bump('announcement');

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Post $announcement)
    {
        $this->ensureAnnouncement($announcement);

        if ($announcement->cover_image_path) {
            Storage::disk('public')->delete($announcement->cover_image_path);
        }

        AuditLogService::log('announcements.deleted', 'post', $announcement->id, [
            'title' => $announcement->title,
            'slug' => $announcement->slug,
        ]);

        $announcement->delete();
        PublicCacheService::bump('announcement');

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    private function ensureAnnouncement(Post $post): void
    {
        if ($post->type !== self::TYPE) {
            abort(404);
        }
    }

    private function preparePayload($request, ?Post $post = null): array
    {
        $data = $request->validated();

        foreach (['body_am', 'body_en'] as $field) {
            if (! empty($data[$field])) {
                $data[$field] = $this->sanitizeRichText($data[$field]);
            }
        }

        $data['title'] = $data['title_en'];
        $data['body'] = $data['body_en'];
        $data['excerpt'] = $data['excerpt_en'] ?? null;
        $data['slug'] = $this->uniqueSlug($data['title_en'], $post?->id);
        $data['is_published'] = $request->boolean('is_published');
        $data['posted_at'] = $post?->posted_at ?? Carbon::now();

        if ($data['is_published']) {
            $data['published_at'] = $data['published_at'] ?? Carbon::now();
        } else {
            $data['published_at'] = null;
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('posts', 'public');
        }

        $data['author_name'] = $data['author_name'] ?? auth()->user()?->name;

        return $data;
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
