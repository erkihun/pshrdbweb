<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->fullText([
                'title_am',
                'title_en',
                'excerpt_am',
                'excerpt_en',
                'body_am',
                'body_en',
            ], 'posts_search_fulltext');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->fullText([
                'title_am',
                'title_en',
                'description_am',
                'description_en',
                'requirements_am',
                'requirements_en',
            ], 'services_search_fulltext');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->fullText([
                'title_am',
                'title_en',
                'description_am',
                'description_en',
            ], 'documents_search_fulltext');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->fullText([
                'title_am',
                'title_en',
                'body_am',
                'body_en',
            ], 'pages_search_fulltext');
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropFullText('posts_search_fulltext');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropFullText('services_search_fulltext');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropFullText('documents_search_fulltext');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropFullText('pages_search_fulltext');
        });
    }
};
