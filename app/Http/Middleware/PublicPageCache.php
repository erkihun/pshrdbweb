<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PublicPageCache
{
    private array $excludedRoutePatterns = [
        'admin.*',
        'login',
        'logout',
        'service-requests.*',
        'appointments.*',
        'locale.switch',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldCache($request, $response)) {
            return $response;
        }

        $response->setPublic();
        $response->setMaxAge(300);
        $response->setSharedMaxAge(300);
        $response->headers->set('Cache-Control', 'public, max-age=300, s-maxage=300');
        $response->setLastModified(now());

        if ($etag = $this->generateEtag($response)) {
            $response->headers->set('ETag', $etag);
        }

        return $response;
    }

    private function shouldCache(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $request->user()) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $contentType = $response->headers->get('Content-Type');
        if ($contentType && ! Str::startsWith($contentType, 'text/html')) {
            return false;
        }

        $routeName = optional($request->route())->getName();

        if ($this->isExcludedRoute($routeName)) {
            return false;
        }

        return true;
    }

    private function isExcludedRoute(?string $routeName): bool
    {
        if (! $routeName) {
            return false;
        }

        foreach ($this->excludedRoutePatterns as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }

    private function generateEtag(Response $response): ?string
    {
        $content = $response->getContent();
        if ($content === false) {
            return null;
        }

        return md5($content);
    }
}
