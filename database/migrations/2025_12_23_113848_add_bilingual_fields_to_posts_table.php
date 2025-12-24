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
            $table->string('title_am')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_am');
            $table->text('body_am')->nullable()->after('body');
            $table->text('body_en')->nullable()->after('body_am');
            $table->string('excerpt_am')->nullable()->after('excerpt');
            $table->string('excerpt_en')->nullable()->after('excerpt_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'title_am',
                'title_en',
                'body_am',
                'body_en',
                'excerpt_am',
                'excerpt_en',
            ]);
        });
    }
};
