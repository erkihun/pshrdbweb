<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'about' => 'About the Public Service Office',
            'mission_vision_values' => 'Mission, Vision, and Values',
            'leadership' => 'Leadership',
            'structure' => 'Organization Structure',
            'history' => 'Institutional History',
        ];

        foreach ($pages as $key => $title) {
            Page::updateOrCreate(
                ['key' => $key],
                [
                    'title_en' => $title,
                    'title_am' => '?' . $title . ' ????',
                    'body_en' => 'This page provides details about ' . strtolower($title) . '. It is managed by the office content team.',
                    'body_am' => '?? ?? ?? ' . $title . ' ???? ??? ??????',
                    'is_published' => true,
                ]
            );
        }
    }
}
