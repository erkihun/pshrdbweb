<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
            \App\Http\Middleware\AssetCacheHeaders::class,
            \App\Http\Middleware\TrackAnalytics::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\ApiLocale::class,
        ]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'api_permission' => \App\Http\Middleware\ApiPermission::class,
            'public.cache' => \App\Http\Middleware\PublicPageCache::class,
            'track.view' => \App\Http\Middleware\TrackPublicView::class,
            'ensure.manage.signage' => \App\Http\Middleware\EnsureCanManageSignage::class,
            'vacancy.rate_limit' => \App\Http\Middleware\VacancyRateLimit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
