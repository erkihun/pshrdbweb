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
        Schema::table('charter_services', function (Blueprint $table) {
            $table->dropColumn([
                'requirements_am',
                'requirements_en',
                'service_time_am',
                'service_time_en',
                'contact_phone',
                'contact_email',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('charter_services', function (Blueprint $table) {
            $table->text('requirements_am')->nullable();
            $table->text('requirements_en')->nullable();
            $table->string('service_time_am', 100)->nullable();
            $table->string('service_time_en', 100)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('contact_email', 255)->nullable();
        });
    }
};
