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
        DB::statement("UPDATE posts SET title = TRIM(REGEXP_REPLACE(title, '<[^>]*>', ''))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Signals that HTML was removed; nothing to revert.
    }
};
