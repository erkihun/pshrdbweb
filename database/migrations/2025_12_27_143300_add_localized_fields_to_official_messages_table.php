<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('official_messages', function (Blueprint $table): void {
            $table->string('name_am')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_am');

            $table->string('title_am')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_am');

            $table->text('message_am')->nullable()->after('message');
            $table->text('message_en')->nullable()->after('message_am');
        });
    }

    public function down(): void
    {
        Schema::table('official_messages', function (Blueprint $table): void {
            $table->dropColumn([
                'name_am',
                'name_en',
                'title_am',
                'title_en',
                'message_am',
                'message_en',
            ]);
        });
    }
};
