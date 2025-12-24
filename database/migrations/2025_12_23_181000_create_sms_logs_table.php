<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('phone');
            $table->text('message');
            $table->string('context_type');
            $table->string('context_id')->nullable();
            $table->string('status')->default('sent');
            $table->json('provider_response')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->index('phone');
            $table->index('context_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
