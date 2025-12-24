<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function news(Request $request)
    {
        return $this->indexByType($request, 'news');
    }

    public function announcements(Request $request)
    {
        return $this->indexByType($request, 'announcement');
    }

    private function indexByType(Request $request, string $type)
    {
        $query = Post::where('type', $type)
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q) use ($search) {
                $q->where('title_am', 'like', '%' . $search . '%')
                    ->orWhere('title_en', 'like', '%' . $search . '%')
                    ->orWhere('excerpt_am', 'like', '%' . $search . '%')
                    ->orWhere('excerpt_en', 'like', '%' . $search . '%');
            });
        }

        return PostResource::collection($query->paginate(10));
    }
}
