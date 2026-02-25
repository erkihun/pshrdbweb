<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BuildDailyAnalyticsStats extends Command
{
    protected $signature = 'analytics:build-daily-stats {date?}';
    protected $description = 'Aggregate pageview and visit counts for a given day.';

    public function handle(): int
    {
        $day = $this->resolveDate();
        $this->info('Building analytics for ' . $day->toDateString());

        $pageviews = DB::table('analytics_pageviews')
            ->whereDate('created_at', $day)
            ->count();

        $visits = DB::table('analytics_visits')
            ->whereDate('first_seen_at', $day)
            ->count();

        $uniqueVisitors = DB::table('analytics_visits')
            ->whereDate('last_seen_at', $day)
            ->distinct('ip_hash')
            ->count('ip_hash');

        $authenticatedUsers = DB::table('analytics_pageviews')
            ->whereDate('created_at', $day)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        DB::table('analytics_daily_stats')->updateOrInsert(
            ['date' => $day->toDateString()],
            [
                'pageviews' => $pageviews,
                'visits' => $visits,
                'unique_visitors' => $uniqueVisitors,
                'authenticated_users' => $authenticatedUsers,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->info('Analytics stats persisted.');

        return Command::SUCCESS;
    }

    protected function resolveDate(): Carbon
    {
        $raw = $this->argument('date');

        if ($raw) {
            return Carbon::parse($raw)->startOfDay();
        }

        return Carbon::yesterday();
    }
}
