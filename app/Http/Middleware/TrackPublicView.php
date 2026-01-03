<?php

namespace App\Http\Middleware;

use App\Models\Document;
use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPublicView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $entity): Response
    {
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        $this->track($entity, $request);

        return $next($request);
    }

    private function track(string $entity, Request $request): void
    {
        $slug = $request->route('slug');
        if (! $slug) {
            return;
        }

        $today = now()->format('Y-m-d');

        if ($entity === 'download') {
            $documentId = Document::where('slug', $slug)->value('id');
            if (! $documentId) {
                return;
            }

            $sessionKey = "view:download:{$documentId}:{$today}";
            if ($this->alreadyTracked($sessionKey)) {
                return;
            }

            Document::where('id', $documentId)->increment('views_count');
            session()->put($sessionKey, true);
            return;
        }

        if (! in_array($entity, ['news', 'announcement'], true)) {
            return;
        }

        $postId = Post::where('slug', $slug)->where('type', $entity)->value('id');
        if (! $postId) {
            return;
        }

        $sessionKey = "view:{$entity}:{$postId}:{$today}";
        if ($this->alreadyTracked($sessionKey)) {
            return;
        }

        Post::where('id', $postId)->increment('views_count');
        session()->put($sessionKey, true);
    }

    private function alreadyTracked(string $key): bool
    {
        return session()->has($key);
    }
}
