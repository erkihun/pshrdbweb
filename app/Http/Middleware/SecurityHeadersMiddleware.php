<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production') && ! $request->isSecure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=()'
        );
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; img-src 'self' data: https:; " .
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://fonts.googleapis.com " .
            "http://localhost:5173 http://localhost:5174 http://127.0.0.1:5173 http://127.0.0.1:5174; " .
            "style-src-elem 'self' 'unsafe-inline' https://fonts.bunny.net https://fonts.googleapis.com " .
            "http://localhost:5173 http://localhost:5174 http://127.0.0.1:5173 http://127.0.0.1:5174; " .
            "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com data:; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173 http://localhost:5174 " .
            "http://127.0.0.1:5173 http://127.0.0.1:5174 https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "script-src-elem 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173 http://localhost:5174 " .
            "http://127.0.0.1:5173 http://127.0.0.1:5174 https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "connect-src 'self' http://localhost:5173 http://localhost:5174 http://127.0.0.1:5173 " .
            "http://127.0.0.1:5174 ws://localhost:5173 ws://localhost:5174 ws://127.0.0.1:5173 ws://127.0.0.1:5174; " .
            "frame-src 'self' https://www.facebook.com https://web.facebook.com; frame-ancestors 'self'; object-src 'none'"
        );

        return $response;
    }
}
