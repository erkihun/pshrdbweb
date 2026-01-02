<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('tender_number')->nullable()->after('slug');
            $table->date('closing_date')->nullable()->after('published_at');
            $table->string('attachment_path')->nullable()->after('closing_date');
            $table->unsignedInteger('view_count')->default(0)->after('attachment_path');
            $table->boolean('is_featured')->default(false)->after('view_count');
        });

        $used = [];
        DB::table('tenders')->select('id', 'title')->orderBy('created_at')->get()->each(function ($record) use (&$used) {
            $slugBase = Str::slug($record->title ?? '') ?: 'tender';
            $slug = $slugBase;
            $counter = 1;

            while (in_array($slug, $used, true)) {
                $slug = "{$slugBase}-{$counter}";
                $counter++;
            }

            $used[] = $slug;

            DB::table('tenders')
                ->where('id', $record->id)
                ->limit(1)
                ->update(['slug' => $slug]);
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE tenders MODIFY slug VARCHAR(255) NOT NULL');
        }

        Schema::table('tenders', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });

        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn(['slug', 'tender_number', 'closing_date', 'attachment_path', 'view_count', 'is_featured']);
        });
    }
};
