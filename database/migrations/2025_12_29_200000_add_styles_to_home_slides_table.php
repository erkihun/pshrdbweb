<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('home_slides', function (Blueprint $table): void {
            $table->string('transition_style')->default('wave')->after('subtitle_am');
            $table->string('content_alignment')->default('center')->after('transition_style');
        });
    }

    public function down(): void
    {
        Schema::table('home_slides', function (Blueprint $table): void {
            $table->dropColumn(['transition_style', 'content_alignment']);
        });
    }
};
