<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedType('news', 10);
        $this->seedType('announcement', 10);
    }

    private function seedType(string $type, int $count): void
    {
        $publishedCount = (int) floor($count * 0.7);
        $draftCount = 2;
        $scheduledCount = $count - $publishedCount - $draftCount;

        for ($i = 0; $i < $publishedCount; $i++) {
            Post::factory()->create([
                'type' => $type,
                'title_en' => Str::title($type) . ' Update ' . ($i + 1),
                'title_am' => '?? ??? ' . ($i + 1),
                'excerpt_en' => 'Public update for ' . $type . ' item ' . ($i + 1) . '.',
                'excerpt_am' => '???? ???? ??? ' . ($i + 1) . '?',
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        for ($i = 0; $i < $draftCount; $i++) {
            Post::factory()->create([
                'type' => $type,
                'title_en' => Str::title($type) . ' Draft ' . ($i + 1),
                'title_am' => '???? ?? ' . ($i + 1),
                'excerpt_en' => 'Draft copy for ' . $type . ' item.',
                'excerpt_am' => '????? ????',
                'is_published' => false,
                'published_at' => null,
            ]);
        }

        for ($i = 0; $i < $scheduledCount; $i++) {
            Post::factory()->create([
                'type' => $type,
                'title_en' => Str::title($type) . ' Scheduled ' . ($i + 1),
                'title_am' => '???? ?? ' . ($i + 1),
                'excerpt_en' => 'Scheduled ' . $type . ' item.',
                'excerpt_am' => '???? ???? ????',
                'is_published' => true,
                'published_at' => now()->addDays(rand(1, 14)),
            ]);
        }
    }
}
