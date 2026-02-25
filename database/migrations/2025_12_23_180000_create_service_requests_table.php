<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_code')->unique();
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->foreignUuid('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('subject');
            $table->text('description');
            $table->string('status')->default('submitted');
            $table->text('admin_note')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamp('submitted_at');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('reference_code');
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
