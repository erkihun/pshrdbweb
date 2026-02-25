<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;

final class LogVisitor
{
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
            VisitorLog::create([
                'ip_address' => $ip,
                'path' => $path,
                'user_agent' => $request->header('User-Agent') ?? '',
                'referer' => $request->headers->get('referer'),
            ]);
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
}
