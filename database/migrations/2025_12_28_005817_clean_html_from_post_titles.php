<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('posts')->orderBy('id')->cursor()
            ->each(fn ($post) => DB::table('posts')
                ->where('id', $post->id)
                ->update(['title' => trim(strip_tags($post->title ?? ''))]));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Signals that HTML was removed; nothing to revert.
    }
};
