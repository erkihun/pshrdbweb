<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BilingualSampleSeeder extends Seeder
{
    public function run(): void
    {
        $newsTitleEn = 'Community Health Update';
        $newsTitleAm = 'የማህበረሰብ ጤና ዝማኔ';

        Post::updateOrCreate(
            ['slug' => Str::slug($newsTitleEn)],
            [
            'title' => $newsTitleEn,
            'title_en' => $newsTitleEn,
            'title_am' => $newsTitleAm,
            'slug' => Str::slug($newsTitleEn),
            'type' => 'news',
            'excerpt' => 'A brief update on this month\'s health outreach.',
            'excerpt_en' => 'A brief update on this month\'s health outreach.',
            'excerpt_am' => 'የዚህ ወር የጤና እርምጃ አጭር ማጠቃለያ።',
            'body' => 'English body copy for the news update.',
            'body_en' => 'English body copy for the news update.',
            'body_am' => 'ለዜናው የአማርኛ የዋና ጽሁፍ ይዘት።',
            'is_published' => true,
            'published_at' => now(),
            ]
        );

        $announcementTitleEn = 'Office Closure Notice';
        $announcementTitleAm = 'የቢሮ መዝጊያ ማስታወቂያ';

        Post::updateOrCreate(
            ['slug' => Str::slug($announcementTitleEn)],
            [
            'title' => $announcementTitleEn,
            'title_en' => $announcementTitleEn,
            'title_am' => $announcementTitleAm,
            'slug' => Str::slug($announcementTitleEn),
            'type' => 'announcement',
            'excerpt' => 'Our office will be closed on Friday.',
            'excerpt_en' => 'Our office will be closed on Friday.',
            'excerpt_am' => 'ቢሮችን አርብ ቀን እንዘጋለን።',
            'body' => 'English announcement details.',
            'body_en' => 'English announcement details.',
            'body_am' => 'የአማርኛ ማስታወቂያ ዝርዝር።',
            'is_published' => true,
            'published_at' => now(),
            ]
        );

        $serviceTitleEn = 'Business License Renewal';
        $serviceTitleAm = 'የንግድ ፍቃድ እድሳት';

        Service::updateOrCreate(
            ['slug' => Str::slug($serviceTitleEn)],
            [
            'title' => $serviceTitleEn,
            'title_en' => $serviceTitleEn,
            'title_am' => $serviceTitleAm,
            'slug' => Str::slug($serviceTitleEn),
            'description' => 'Renew your business license quickly.',
            'description_en' => 'Renew your business license quickly.',
            'description_am' => 'የንግድ ፍቃድዎን በፍጥነት ያዘምኑ።',
            'requirements' => 'Completed renewal form.',
            'requirements_en' => 'Completed renewal form.',
            'requirements_am' => 'የተሞላ የእድሳት ቅጽ።',
            'is_active' => true,
            'sort_order' => 1,
            ]
        );

        $category = DocumentCategory::firstOrCreate(
            ['slug' => 'public-forms'],
            [
                'name' => 'Public Forms',
                'name_am' => 'Public Forms',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $filePath = 'documents/sample.pdf';
        if (! Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->put($filePath, 'Sample bilingual document.');
        }

        $documentTitleEn = 'Citizen Application Form';
        $documentTitleAm = 'የነዋሪ ማመልከቻ ቅጽ';

        Document::updateOrCreate(
            ['slug' => Str::slug($documentTitleEn)],
            [
                'document_category_id' => $category->id,
                'title' => $documentTitleEn,
                'title_en' => $documentTitleEn,
                'title_am' => $documentTitleAm,
                'slug' => Str::slug($documentTitleEn),
                'description' => 'Download the citizen application form.',
                'description_en' => 'Download the citizen application form.',
                'description_am' => 'የነዋሪ ማመልከቻ ቅጽን ያውርዱ።',
                'file_path' => $filePath,
                'file_type' => 'pdf',
                'file_size' => Storage::disk('public')->size($filePath),
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}
