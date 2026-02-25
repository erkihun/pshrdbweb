<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class VacancyRateLimit
{
    public function handle(Request $request, Closure $next, string $context): Response
    {
        if (app()->environment('local')) {
            return $next($request);
        }

        if ($request->isMethod('get')) {
            return $next($request);
        }

        $limits = [
            'apply' => ['max' => 3, 'decay' => 10],
            'track' => ['max' => 10, 'decay' => 10],
        ];

        $limit = $limits[$context] ?? ['max' => 10, 'decay' => 10];
        $key = sprintf('vacancy:%s:%s', $context, $request->ip());

        if (RateLimiter::tooManyAttempts($key, $limit['max'])) {
            abort(429);
        }

        RateLimiter::hit($key, $limit['decay'] * 60);

        return $next($request);
    }
}
