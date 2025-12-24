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
        Schema::table('documents', function (Blueprint $table) {
            $table->string('title_am')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_am');
            $table->text('description_am')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'title_am',
                'title_en',
                'description_am',
                'description_en',
            ]);
        });
    }
};
