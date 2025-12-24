<?php

namespace Database\Seeders;

use App\Enums\HomeSection;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => HomeSection::HERO->value],
            [
                'value' => [
                    'title' => 'Public Service Portal',
                    'subtitle' => 'Fast, reliable services for citizens and businesses.',
                    'cta_label' => 'Explore Services',
                    'cta_url' => '/services',
                    'background_image' => null,
                ],
            ]
        );

        Setting::updateOrCreate(
            ['key' => HomeSection::SERVICES_HIGHLIGHT->value],
            [
                'value' => [
                    'title' => 'Featured Services',
                    'items' => [
                        ['title' => 'Business Licensing', 'description' => 'Apply or renew your business license.'],
                        ['title' => 'Citizen Support', 'description' => 'Access essential citizen services.'],
                        ['title' => 'Document Services', 'description' => 'Find forms and official documents.'],
                    ],
                ],
            ]
        );

        Setting::updateOrCreate(
            ['key' => HomeSection::NEWS_HIGHLIGHT->value],
            [
                'value' => [
                    'title' => 'Latest Updates',
                    'items' => [
                        ['title' => 'Service expansion announced', 'description' => 'New service centers are opening soon.'],
                        ['title' => 'Digital services upgrade', 'description' => 'Faster online services for citizens.'],
                    ],
                ],
            ]
        );

        Setting::updateOrCreate(
            ['key' => HomeSection::STATS->value],
            [
                'value' => [
                    'title' => 'Service Impact',
                    'items' => [
                        ['label' => 'Annual Requests', 'value' => '120K+'],
                        ['label' => 'Processing Time', 'value' => '3 Days'],
                        ['label' => 'Service Centers', 'value' => '18'],
                        ['label' => 'Staff Members', 'value' => '240+'],
                    ],
                ],
            ]
        );

        Setting::updateOrCreate(
            ['key' => HomeSection::FOOTER_LINKS->value],
            [
                'value' => [
                    'links' => [
                        ['label' => 'Services', 'url' => '/services'],
                        ['label' => 'Downloads', 'url' => '/downloads'],
                        ['label' => 'News', 'url' => '/news'],
                        ['label' => 'Contact', 'url' => '/contact'],
                    ],
                ],
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'office.hours'],
            [
                'value' => [
                    'start' => '08:30',
                    'end' => '17:30',
                ],
            ]
        );
    }
}
