<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response->isSuccessful()) {
            return $response;
        }

        $path = ltrim($request->path(), '/');

        if (str_starts_with($path, 'build/')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        } elseif (str_starts_with($path, 'storage/')) {
            $response->headers->set('Cache-Control', 'public, max-age=86400');
        }

        return $response;
    }
}
