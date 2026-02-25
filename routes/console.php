<?php

use App\Models\AuditLog;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    AuditLog::where('created_at', '<', now()->subDays(180))->delete();
})->daily()->name('prune-audit-logs');

Schedule::command('analytics:build-daily-stats')->daily()->name('analytics.daily-stats');
