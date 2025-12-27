<?php

namespace App\Http\Controllers;

use App\Actions\OrgStats\SummarizeOrgStatsAction;
use App\Enums\HomeSection;
use App\Models\Organization;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class HomepageController extends Controller
{
    public function __invoke(SummarizeOrgStatsAction $summaryAction)
    {
        $sections = $this->loadSections();

        $organizations = Organization::with('orgStats')->orderBy('name')->get();
        $organizationSummaries = $organizations->map(fn (Organization $organization) => [
            'name' => $organization->name,
            'code' => $organization->code,
            'total' => $organization->orgStats->sum(fn ($stat) => $stat->male + $stat->female + $stat->other),
            'male' => $organization->orgStats->sum('male'),
            'female' => $organization->orgStats->sum('female'),
            'other' => $organization->orgStats->sum('other'),
            'summary' => $summaryAction->execute($organization->orgStats),
        ]);

        $citizenTotals = [
            'male' => $organizationSummaries->sum('male'),
            'female' => $organizationSummaries->sum('female'),
            'other' => $organizationSummaries->sum('other'),
        ];
        $citizenTotals['total'] = $citizenTotals['male'] + $citizenTotals['female'] + $citizenTotals['other'];

        return view('welcome', compact('sections', 'organizationSummaries', 'citizenTotals'));
    }

    private function loadSections(): array
    {
        return Cache::remember('home.settings', 600, function () {
            $defaults = $this->defaultSections();
            $stored = Setting::whereIn('key', HomeSection::keys())
                ->get()
                ->keyBy('key')
                ->map(fn ($setting) => $setting->value ?? [])
                ->toArray();

            return array_replace_recursive($defaults, $stored);
        });
    }

    public function defaultSections(): array
    {
        return [
            HomeSection::HERO->value => [
                'title' => 'Let\'s get started.',
                'subtitle' => 'Laravel has an incredibly rich ecosystem. We suggest starting with the following.',
                'cta_text' => 'Visit Dashboard',
                'cta_url' => '/dashboard',
                'background_image' => null,
            ],
            HomeSection::SERVICES_HIGHLIGHT->value => [
                'title' => 'Top Services',
                'items' => [
                    ['title' => 'Licensing', 'description' => 'Permit and licensing support.', 'icon' => null],
                    ['title' => 'Community Health', 'description' => 'Public health services.', 'icon' => null],
                    ['title' => 'Civil Services', 'description' => 'Official records and certificates.', 'icon' => null],
                ],
            ],
            HomeSection::NEWS_HIGHLIGHT->value => [
                'title' => 'Latest News',
                'items' => [
                    ['title' => 'Policy Updates', 'description' => 'Latest changes in public policy.', 'url' => '/news'],
                    ['title' => 'Announcements', 'description' => 'Important announcements.', 'url' => '/announcements'],
                    ['title' => 'Events', 'description' => 'Upcoming public events.', 'url' => '/news'],
                ],
            ],
            HomeSection::STATS->value => [
                'items' => [
                    ['label' => 'Citizens Served', 'value' => '12K+'],
                    ['label' => 'Active Services', 'value' => '45'],
                    ['label' => 'Downloads', 'value' => '3.2K'],
                    ['label' => 'Offices', 'value' => '8'],
                ],
            ],
            HomeSection::FOOTER_LINKS->value => [
                'items' => [
                    ['label' => 'Services', 'url' => '/services'],
                    ['label' => 'Downloads', 'url' => '/downloads'],
                    ['label' => 'News', 'url' => '/news'],
                    ['label' => 'About', 'url' => '/about'],
                ],
            ],
        ];
    }
}
