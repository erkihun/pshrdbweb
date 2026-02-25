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
            $table->unsignedBigInteger('views_count')->default(0);
            $table->index('views_count');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('views_count')->default(0)->after('is_published');
            $table->unsignedBigInteger('downloads_count')->default(0)->after('views_count');
            $table->index('views_count');
            $table->index('downloads_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['views_count']);
            $table->dropColumn('views_count');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['views_count']);
            $table->dropIndex(['downloads_count']);
            $table->dropColumn(['views_count', 'downloads_count']);
        });
    }
};
