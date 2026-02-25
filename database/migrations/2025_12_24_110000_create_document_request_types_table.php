<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_request_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name_am');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->text('requirements_am')->nullable();
            $table->text('requirements_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_request_types');
    }
};
