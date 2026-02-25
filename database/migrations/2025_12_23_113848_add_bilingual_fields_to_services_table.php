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
        Schema::table('services', function (Blueprint $table) {
            $table->string('title_am')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_am');
            $table->text('description_am')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_am');
            $table->text('requirements_am')->nullable()->after('requirements');
            $table->text('requirements_en')->nullable()->after('requirements_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'title_am',
                'title_en',
                'description_am',
                'description_en',
                'requirements_am',
                'requirements_en',
            ]);
        });
    }
};
