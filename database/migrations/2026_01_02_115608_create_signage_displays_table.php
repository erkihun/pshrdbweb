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
        Schema::create('signage_displays', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignUuid('signage_template_id')->constrained('signage_templates', 'uuid')->cascadeOnDelete();
            $table->string('title_am')->nullable();
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->json('payload')->nullable();
            $table->integer('refresh_seconds')->default(60);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signage_displays');
    }
};
