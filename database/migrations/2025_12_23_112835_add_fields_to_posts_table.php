<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('excerpt')->nullable()->after('body');
            $table->enum('type', ['news', 'announcement'])->default('news')->after('excerpt');
            $table->string('seo_title')->nullable()->after('type');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->boolean('is_published')->default(false)->after('seo_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'excerpt',
                'type',
                'seo_title',
                'seo_description',
                'is_published',
            ]);
        });
    }
};
