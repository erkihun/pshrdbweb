<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PublicPageCache
{
    /**
     * Routes that should receive long-lived cache headers.
     *
     * @var string[]
     */
    private array $routes = [
        'home',
        'pages.about',
        'staff.index',
        'citizen-charter.index',
        'organization.contact',
    ];

    /**
     * Paths that should receive rapid cache headers even when route names are missing.
     *
     * @var string[]
     */
    private array $paths = [
        '/',
        'about',
        'staff',
        'citizen-charter',
        'organization/contact',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldCache($request, $response)) {
            return $response;
        }

        $response->setPublic();
        $response->setMaxAge(300);
        $response->setSharedMaxAge(600);
        $response->headers->set('Cache-Control', 'public, max-age=300, s-maxage=600');

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
        if ($routeName && in_array($routeName, $this->routes, true)) {
            return true;
        }

        $path = trim($request->path(), '/');
        $path = $path === '' ? '/' : $path;
        if (in_array($path, $this->paths, true)) {
            return true;
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
