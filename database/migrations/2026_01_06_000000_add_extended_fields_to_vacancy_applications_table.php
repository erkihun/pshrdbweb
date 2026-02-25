<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'vacancy_applications';

        $columns = [
            'date_of_birth' => fn (Blueprint $table) => $table->date('date_of_birth')->nullable(),
            'gender' => fn (Blueprint $table) => $table->string('gender')->nullable(),
            'disability_status' => fn (Blueprint $table) => $table->boolean('disability_status')->nullable(),
            'education_level' => fn (Blueprint $table) => $table->string('education_level')->nullable(),
            'field_of_study' => fn (Blueprint $table) => $table->string('field_of_study')->nullable(),
            'university_name' => fn (Blueprint $table) => $table->string('university_name')->nullable(),
            'graduation_year' => fn (Blueprint $table) => $table->year('graduation_year')->nullable(),
            'gpa' => fn (Blueprint $table) => $table->decimal('gpa', 3, 2)->nullable(),
            'education_document_path' => fn (Blueprint $table) => $table->string('education_document_path')->nullable(),
            'profile_photo_path' => fn (Blueprint $table) => $table->string('profile_photo_path')->nullable(),
            'address' => fn (Blueprint $table) => $table->string('address')->nullable(),
            'national_id_number' => fn (Blueprint $table) => $table->string('national_id_number')->nullable(),
        ];

        foreach ($columns as $name => $callback) {
            if (! Schema::hasColumn($tableName, $name)) {
                Schema::table($tableName, function (Blueprint $table) use ($callback) {
                    $callback($table);
                });
            }
        }

        if (! Schema::hasColumn($tableName, 'user_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tableName = 'vacancy_applications';

        if (Schema::hasColumn($tableName, 'user_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        $columns = [
            'date_of_birth',
            'gender',
            'disability_status',
            'education_level',
            'field_of_study',
            'university_name',
            'graduation_year',
            'gpa',
            'education_document_path',
            'profile_photo_path',
            'address',
            'national_id_number',
            'user_id',
        ];

        foreach ($columns as $name) {
            if (Schema::hasColumn($tableName, $name)) {
                Schema::table($tableName, function (Blueprint $table) use ($name) {
                    $table->dropColumn($name);
                });
            }
        }
    }
};
