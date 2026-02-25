<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mous', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('reference_no')->nullable();
            $table->date('signed_at')->nullable();
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
            $table->longText('scope_am')->nullable();
            $table->longText('scope_en')->nullable();
            $table->longText('key_areas_am')->nullable();
            $table->longText('key_areas_en')->nullable();
            $table->string('attachment_path')->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('partner_id');
            $table->index('status');
            $table->index('is_published');
            $table->index('signed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mous');
    }
};
