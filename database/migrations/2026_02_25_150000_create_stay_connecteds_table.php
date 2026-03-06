<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stay_connecteds', function (Blueprint $table) {
            $table->id();
            $table->string('title_am')->nullable();
            $table->string('title_en')->nullable();
            $table->string('url')->nullable();
            $table->string('embed_url');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stay_connecteds');
    }
};
