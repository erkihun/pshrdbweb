<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('home_slides', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image_path'); // storage path on public disk
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            // optional bilingual fields (your app uses bilingual content elsewhere)
            $table->string('title_am')->nullable();
            $table->string('subtitle_am')->nullable();

            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_slides');
    }
};
