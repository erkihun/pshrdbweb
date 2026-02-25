<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminSessionTimeout
{
    private const LAST_ACTIVITY_KEY = 'admin_last_activity_at';

    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $timeoutSeconds = max((int) config('session.lifetime', 120), 1) * 60;
        $lastActivity = (int) $request->session()->get(self::LAST_ACTIVITY_KEY, 0);
        $now = time();

        if ($lastActivity > 0 && ($now - $lastActivity) > $timeoutSeconds) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', __('common.messages.admin_session_expired'));
        }

        $request->session()->put(self::LAST_ACTIVITY_KEY, $now);

        return $next($request);
    }
}
