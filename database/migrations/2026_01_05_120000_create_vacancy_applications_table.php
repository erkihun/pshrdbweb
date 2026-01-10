<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancy_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vacancy_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('cv_path');
            $table->string('cv_name')->nullable();
            $table->string('status')->default('submitted');
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->foreign('vacancy_id')
                ->references('id')
                ->on('vacancies')
                ->onDelete('cascade');

            $table->index('vacancy_id');
            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancy_applications');
    }
};
