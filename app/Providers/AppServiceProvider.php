<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\TicketCreated;
use App\Events\TicketReplied;
use App\Events\ServiceRequestStatusUpdated;
use App\Listeners\SendTicketCreatedSms;
use App\Listeners\SendTicketReplySms;
use App\Listeners\SendServiceRequestStatusSms;
use App\Contracts\SmsProvider;
use App\Services\HttpSmsProvider;
use App\Services\SiteSettingsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsProvider::class, HttpSmsProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
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

        Broadcast::routes();

        $channels = base_path('routes/channels.php');

        if (file_exists($channels)) {
            require $channels;
        }

        $siteSettings = app(SiteSettingsService::class);
        view()->share('site_settings', $siteSettings->all());
    }
}
