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
        Schema::create('charter_services', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('department_id');
            $table->string('name_am');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->text('prerequisites_am')->nullable();
            $table->text('prerequisites_en')->nullable();
            $table->text('requirements_am')->nullable();
            $table->text('requirements_en')->nullable();
            $table->string('service_time_am', 100)->nullable();
            $table->string('service_time_en', 100)->nullable();
            $table->string('service_place_am', 255)->nullable();
            $table->string('service_place_en', 255)->nullable();
            $table->json('working_days')->nullable();
            $table->string('working_hours_start', 5)->nullable();
            $table->string('working_hours_end', 5)->nullable();
            $table->string('break_time_start', 5)->nullable();
            $table->string('break_time_end', 5)->nullable();
            $table->enum('service_delivery_mode', ['in_person', 'online', 'both'])->nullable();
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->string('fee_currency', 10)->default('ETB');
            $table->string('contact_phone', 50)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->text('other_info_am')->nullable();
            $table->text('other_info_en')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->cascadeOnDelete();
            $table->index('department_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charter_services');
    }
};
