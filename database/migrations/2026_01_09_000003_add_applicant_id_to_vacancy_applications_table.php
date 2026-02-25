<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vacancy_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('vacancy_applications', 'applicant_id')) {
                $table->foreignId('applicant_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('applicants')
                    ->cascadeOnDelete();
            }
        });

        if (Schema::hasColumn('vacancy_applications', 'user_id')) {
            $userIds = DB::table('vacancy_applications')
                ->whereNotNull('user_id')
                ->distinct()
                ->pluck('user_id')
                ->values();

            if ($userIds->isNotEmpty()) {
                $userIdToApplicantId = [];

                DB::table('users')
                    ->whereIn('id', $userIds)
                    ->orderBy('id')
                    ->chunk(100, function ($users) use (&$userIdToApplicantId) {
                        foreach ($users as $user) {
                            $existingApplicant = DB::table('applicants')
                                ->where('email', $user->email)
                                ->first();

                            if ($existingApplicant) {
                                $userIdToApplicantId[$user->id] = $existingApplicant->id;
                                continue;
                            }

                            $applicantId = DB::table('applicants')->insertGetId([
                                'uuid' => (string) Str::uuid(),
                                'full_name' => $user->name ?? $user->email,
                                'phone' => $user->phone ?? '',
                                'national_id_number' => $user->national_id ?? null,
                                'gender' => $user->gender ?? null,
                                'email' => $user->email,
                                'password' => $user->password,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            $userIdToApplicantId[$user->id] = $applicantId;
                        }
                    });

                foreach ($userIdToApplicantId as $userId => $applicantId) {
                    DB::table('vacancy_applications')
                        ->where('user_id', $userId)
                        ->update(['applicant_id' => $applicantId]);
                }
            }
        }

        $constraint = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'vacancy_applications'
              AND COLUMN_NAME = 'user_id'
              AND CONSTRAINT_NAME <> 'PRIMARY'
        ");

        if ($constraint?->CONSTRAINT_NAME) {
            DB::statement('ALTER TABLE vacancy_applications DROP FOREIGN KEY ' . $constraint->CONSTRAINT_NAME);
        }

        $hasNullApplicants = DB::table('vacancy_applications')
            ->whereNull('applicant_id')
            ->exists();

        if (! $hasNullApplicants) {
            DB::statement('ALTER TABLE vacancy_applications MODIFY applicant_id BIGINT UNSIGNED NOT NULL');
        }
    }

    public function down(): void
    {
        Schema::table('vacancy_applications', function (Blueprint $table) {
            if (Schema::hasColumn('vacancy_applications', 'applicant_id')) {
                $table->dropConstrainedForeignId('applicant_id');
            }
        });
    }
};
