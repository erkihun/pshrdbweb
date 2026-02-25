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
        Schema::create('signage_templates', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name_am')->nullable();
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->enum('orientation', ['portrait', 'landscape'])->default('portrait');
            $table->enum('layout', ['header_two_cols_footer', 'header_body_footer', 'split_three_rows'])->default('header_two_cols_footer');
            $table->json('schema')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signage_templates');
    }
};
