<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->boolean('disability_status')->default(false);
            $table->string('education_level')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('university_name')->nullable();
            $table->year('graduation_year')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('education_document_path')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->index();
            $table->string('national_id_number')->nullable()->index();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
