<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('official_messages', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('title'); // Position (e.g. Director General)
            $table->text('message');

            $table->string('photo_path')->nullable(); // only one photo
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('official_messages');
    }
};
