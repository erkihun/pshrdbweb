<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class LogVisitor
{
    private const REFERER_MAX_LENGTH = 255;

    public function handle(Request $request, Closure $next)
    {
        if (app()->environment(['local', 'testing'])) {
            return $next($request);
        }

        $response = $next($request);

        if (!$this->shouldTrack($request)) {
            return $response;
        }

        $ip = $request->ip() ?? '0.0.0.0';
        $path = '/' . ltrim($request->path(), '/');

        $exists = VisitorLog::where('ip_address', $ip)
            ->where('path', $path)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if (! $exists) {
            try {
                VisitorLog::create([
                    'ip_address' => $ip,
                    'path' => $path,
                    'user_agent' => $request->header('User-Agent') ?? '',
                    'referer' => $this->sanitizeReferer(
                        $request->headers->get('referer') ?? $request->headers->get('referrer')
                    ),
                ]);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if (! $request->isMethod('GET') || $request->ajax() || $request->expectsJson()) {
            return false;
        }

        if ($request->is('admin*') || $request->is('api*') || $request->is('_ignition*') || $request->is('horizon*')) {
            return false;
        }

        return true;
    }

    private function sanitizeReferer(?string $referer): ?string
    {
        if ($referer === null || $referer === '') {
            return null;
        }

        return Str::substr($referer, 0, self::REFERER_MAX_LENGTH);
    }
}
