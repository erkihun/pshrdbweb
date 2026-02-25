<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'am'));

        if (! in_array($locale, ['am', 'en'], true)) {
            $locale = config('app.locale', 'am');
            session(['locale' => $locale]);
        }

        app()->setLocale($locale);

        if (! session()->has('locale')) {
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
