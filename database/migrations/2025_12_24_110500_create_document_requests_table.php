<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_code')->unique();
            $table->foreignUuid('document_request_type_id')->constrained('document_request_types')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('id_number')->nullable();
            $table->text('address_am')->nullable();
            $table->text('address_en')->nullable();
            $table->text('purpose')->nullable();
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'ready', 'rejected', 'delivered'])->default('submitted');
            $table->text('admin_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
