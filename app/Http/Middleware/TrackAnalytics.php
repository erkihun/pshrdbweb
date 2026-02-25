<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    protected array $assetExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'svg', 'webp', 'gif', 'woff', 'woff2', 'map'];
    protected array $skipPrefixes = ['admin', 'api', 'livewire', 'storage', 'vendor'];

    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment(['local', 'testing'])) {
            return $next($request);
        }

        if (! in_array($request->method(), ['GET', 'HEAD'])) {
            return $next($request);
        }

        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $response = $next($request);

        $sessionId = session()->getId();
        if (empty($sessionId)) {
            return $response;
        }

        $this->recordView($request, $response, $sessionId);

        return $response;
    }

    protected function recordView(Request $request, Response $response, string $sessionId): void
    {
        $now = Carbon::now();
        $path = $this->normalizePath($request);
        $referrer = $request->headers->get('referer') ?? $request->headers->get('referrer');
        $device = $this->detectDevice($request->userAgent());
        $hashedIp = $this->hashIp($request->ip());
        $userId = Auth::id();

        try {
            DB::transaction(function () use (
                $path,
                $referrer,
                $device,
                $hashedIp,
                $now,
                $request,
                $response,
                $sessionId,
                $userId
            ): void {
                $visit = DB::table('analytics_visits')->where('session_id', $sessionId)->first();

                if ($visit) {
                    DB::table('analytics_visits')->where('id', $visit->id)->update([
                        'user_id' => $userId,
                        'ip_hash' => $hashedIp,
                        'user_agent' => $request->userAgent(),
                        'device_type' => $device['type'],
                        'browser' => $device['browser'],
                        'os' => $device['os'],
                        'referrer' => $referrer,
                        'last_path' => $path,
                        'last_seen_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $visitId = $visit->id;
                } else {
                    $visitId = DB::table('analytics_visits')->insertGetId([
                        'uuid' => (string) Str::uuid(),
                        'session_id' => $sessionId,
                        'user_id' => $userId,
                        'ip_hash' => $hashedIp,
                        'user_agent' => $request->userAgent(),
                        'device_type' => $device['type'],
                        'browser' => $device['browser'],
                        'os' => $device['os'],
                        'referrer' => $referrer,
                        'landing_path' => $path,
                        'last_path' => $path,
                        'first_seen_at' => $now,
                        'last_seen_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                DB::table('analytics_pageviews')->insert([
                    'uuid' => (string) Str::uuid(),
                    'visit_id' => $visitId,
                    'user_id' => $userId,
                    'path' => $path,
                    'full_url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'status_code' => $response->getStatusCode(),
                    'referrer' => $referrer,
                    'created_at' => $now,
                ]);

                DB::table('analytics_visits')->where('id', $visitId)->increment('pageviews_count');
            });
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    protected function shouldSkip(Request $request): bool
    {
        $path = ltrim($request->path(), '/');
        foreach ($this->skipPrefixes as $prefix) {
            if (Str::startsWith($path, $prefix)) {
                return true;
            }
        }

        if ($request->is('favicon.ico')) {
            return true;
        }

        if ($extension = strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            return in_array($extension, $this->assetExtensions, true);
        }

        return false;
    }

    protected function normalizePath(Request $request): string
    {
        $path = '/' . trim($request->path(), '/');

        return $path === '/' ? $path : rtrim($path, '/');
    }

    protected function detectDevice(?string $userAgent): array
    {
        $agent = $userAgent ?? '';
        $lower = strtolower($agent);
        $type = 'desktop';
        if (Str::contains($lower, ['bot', 'crawl', 'spider'])) {
            $type = 'bot';
        } elseif (Str::contains($lower, ['ipad', 'tablet'])) {
            $type = 'tablet';
        } elseif (Str::contains($lower, ['mobile', 'android', 'iphone'])) {
            $type = 'mobile';
        }

        $browser = 'Unknown';
        foreach (['Edge', 'Chrome', 'Firefox', 'Safari', 'Opera'] as $candidate) {
            if (str_contains($agent, $candidate)) {
                $browser = $candidate;
                break;
            }
        }

        $os = 'Unknown';
        foreach (['Windows', 'Mac OS', 'Linux', 'Android', 'iOS', 'iPadOS'] as $candidate) {
            if (str_contains($agent, $candidate)) {
                $os = $candidate;
                break;
            }
        }

        return [
            'type' => $type,
            'browser' => $browser,
            'os' => $os,
        ];
    }

    protected function hashIp(?string $ip): string
    {
        $salt = config('app.key', '');
        return hash('sha256', ($ip ?? '') . $salt);
    }
}
