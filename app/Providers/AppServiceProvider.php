<?php

namespace App\Providers;

use App\Contracts\SmsProvider;
use App\Events\ServiceRequestStatusUpdated;
use App\Events\TicketCreated;
use App\Events\TicketReplied;
use App\Http\Middleware\LogVisitor;
use App\Listeners\SendServiceRequestStatusSms;
use App\Listeners\SendTicketCreatedSms;
use App\Listeners\SendTicketReplySms;
use App\Models\VisitorLog;
use App\Services\HttpSmsProvider;
use App\Services\SiteSettingsService;
use App\Models\Organization;
use App\Models\VacancyApplication;
use App\Policies\OrganizationPolicy;
use App\Policies\VacancyApplicationPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsProvider::class, HttpSmsProvider::class);
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('tickets', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('service_request_submit', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('service_request_track', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        RateLimiter::for('service_feedback', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('document_request_submit', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('document_request_track', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        RateLimiter::for('appointment_book', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('appointment_track', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        RateLimiter::for('appointment_cancel', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('applicant_dashboard', function (Request $request) {
            return Limit::perMinute(30)->by(($request->user()?->id ?? '') . $request->ip());
        });

        RateLimiter::for('chat_start', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('chat_message', function (Request $request) {
            $session = $request->route('chatSession');

            return Limit::perMinute(20)->by($request->ip() . ($session?->id ?? ''));
        });

        Event::listen(TicketCreated::class, SendTicketCreatedSms::class);
        Event::listen(TicketReplied::class, SendTicketReplySms::class);
        Event::listen(ServiceRequestStatusUpdated::class, SendServiceRequestStatusSms::class);
        Event::listen(Login::class, fn (Login $event) => $event->user->forceFill(['last_login_at' => now()])->saveQuietly());

        Broadcast::routes();

        $channels = base_path('routes/channels.php');

        if (file_exists($channels)) {
            require $channels;
        }

        $siteSettings = app(SiteSettingsService::class);
        view()->share('site_settings', $siteSettings->all());

        if (! $this->app->environment(['local', 'testing']) && $this->app->resolved('router')) {
            $router = $this->app->make(\Illuminate\Routing\Router::class);
            $router->pushMiddlewareToGroup('web', LogVisitor::class);
        }

        View::composer('layouts.public-footer', function ($view) {
            $count = Cache::remember('visitor.count.total', now()->addMinutes(5), fn () => VisitorLog::count());
            $view->with('visitorCount', $count);
        });

        Gate::policy(VacancyApplication::class, VacancyApplicationPolicy::class);
        Gate::policy(Organization::class, OrganizationPolicy::class);
    }
}
