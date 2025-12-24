<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('locale');

        if (! $locale) {
            $header = $request->header('Accept-Language');
            if ($header) {
                $locale = substr($header, 0, 2);
            }
        }

        if (in_array($locale, ['am', 'en'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
