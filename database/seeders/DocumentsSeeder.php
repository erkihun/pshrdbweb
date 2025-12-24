<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = DocumentCategory::all();
        if ($categories->isEmpty()) {
            return;
        }

        Storage::disk('public')->makeDirectory('documents');

        for ($i = 1; $i <= 20; $i++) {
            $titleEn = 'Document ' . $i;
            $slug = Str::slug($titleEn) . '-' . Str::lower(Str::random(4));
            $filePath = 'documents/' . $slug . '.pdf';

            if (! Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->put($filePath, 'Sample document content for ' . $titleEn);
            }

            Document::create([
                'document_category_id' => $categories->random()->id,
                'title' => $titleEn,
                'title_en' => $titleEn,
                'title_am' => '??? ' . $i,
                'slug' => $slug,
                'description' => 'Download the document for more details.',
                'description_en' => 'Download the document for more details.',
                'description_am' => '???? ??? ????? ???? ?????',
                'file_path' => $filePath,
                'file_type' => 'pdf',
                'file_size' => Storage::disk('public')->size($filePath),
                'is_published' => true,
                'published_at' => now()->subDays(rand(0, 60)),
            ]);
        }
    }
}
