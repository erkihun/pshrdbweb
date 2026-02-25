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
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_code')->unique();
            $table->uuid('appointment_service_id');
            $table->uuid('appointment_slot_id');
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['booked', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('booked');
            $table->timestamp('booked_at')->useCurrent();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->foreign('appointment_service_id')
                ->references('id')
                ->on('appointment_services')
                ->cascadeOnDelete();

            $table->foreign('appointment_slot_id')
                ->references('id')
                ->on('appointment_slots')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
