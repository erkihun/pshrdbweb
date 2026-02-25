<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasApplicantId = Schema::hasColumn('vacancy_applications', 'applicant_id');

        if ($hasApplicantId) {
            DB::statement("
                DELETE va FROM vacancy_applications va
                INNER JOIN vacancy_applications vb
                    ON va.applicant_id = vb.applicant_id
                    AND (
                        va.created_at > vb.created_at
                        OR (va.created_at = vb.created_at AND va.id > vb.id)
                    )
                WHERE va.applicant_id IS NOT NULL
            ");
        }

        if ($hasApplicantId) {
            $uniqueIndex = DB::selectOne("
                SELECT COUNT(1) AS count
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                  AND table_name = 'vacancy_applications'
                  AND index_name = 'vacancy_applications_applicant_id_unique'
            ");

            if (! $uniqueIndex || (int) $uniqueIndex->count === 0) {
                Schema::table('vacancy_applications', function (Blueprint $table) {
                    $table->unique('applicant_id');
                });
            }

            $applicantIndex = DB::selectOne("
                SELECT COUNT(1) AS count
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                  AND table_name = 'vacancy_applications'
                  AND index_name = 'vacancy_applications_applicant_id_index'
            ");

            if (! $applicantIndex || (int) $applicantIndex->count === 0) {
                Schema::table('vacancy_applications', function (Blueprint $table) {
                    $table->index('applicant_id');
                });
            }

            $createdAtIndex = DB::selectOne("
                SELECT COUNT(1) AS count
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                  AND table_name = 'vacancy_applications'
                  AND index_name = 'vacancy_applications_created_at_index'
            ");

            if (! $createdAtIndex || (int) $createdAtIndex->count === 0) {
                Schema::table('vacancy_applications', function (Blueprint $table) {
                    $table->index('created_at');
                });
            }

            $statusIndex = DB::selectOne("
                SELECT COUNT(1) AS count
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                  AND table_name = 'vacancy_applications'
                  AND index_name = 'vacancy_applications_status_index'
            ");

            if (! $statusIndex || (int) $statusIndex->count === 0) {
                Schema::table('vacancy_applications', function (Blueprint $table) {
                    $table->index('status');
                });
            }
        }

        $applicantsPhone = DB::selectOne("
            SELECT COUNT(1) AS count
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'applicants'
              AND index_name = 'applicants_phone_index'
        ");

        if (! $applicantsPhone || (int) $applicantsPhone->count === 0) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->index('phone');
            });
        }

        $applicantsEmail = DB::selectOne("
            SELECT COUNT(1) AS count
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'applicants'
              AND index_name = 'applicants_email_index'
        ");

        if (! $applicantsEmail || (int) $applicantsEmail->count === 0) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->index('email');
            });
        }
    }

    public function down(): void
    {
        $hasApplicantId = Schema::hasColumn('vacancy_applications', 'applicant_id');

        if ($hasApplicantId) {
            $indexes = [
                'vacancy_applications_applicant_id_unique' => fn (Blueprint $table) => $table->dropUnique('vacancy_applications_applicant_id_unique'),
                'vacancy_applications_applicant_id_index' => fn (Blueprint $table) => $table->dropIndex('vacancy_applications_applicant_id_index'),
                'vacancy_applications_created_at_index' => fn (Blueprint $table) => $table->dropIndex('vacancy_applications_created_at_index'),
                'vacancy_applications_status_index' => fn (Blueprint $table) => $table->dropIndex('vacancy_applications_status_index'),
            ];

            foreach ($indexes as $indexName => $dropper) {
                $exists = DB::selectOne("
                    SELECT COUNT(1) AS count
                    FROM information_schema.statistics
                    WHERE table_schema = DATABASE()
                      AND table_name = 'vacancy_applications'
                      AND index_name = '{$indexName}'
                ");

                if ($exists && (int) $exists->count > 0) {
                    Schema::table('vacancy_applications', function (Blueprint $table) use ($dropper) {
                        $dropper($table);
                    });
                }
            }
        }

        $applicantIndexes = [
            'applicants_phone_index' => fn (Blueprint $table) => $table->dropIndex('applicants_phone_index'),
            'applicants_email_index' => fn (Blueprint $table) => $table->dropIndex('applicants_email_index'),
        ];

        foreach ($applicantIndexes as $indexName => $dropper) {
            $exists = DB::selectOne("
                SELECT COUNT(1) AS count
                FROM information_schema.statistics
                WHERE table_schema = DATABASE()
                  AND table_name = 'applicants'
                  AND index_name = '{$indexName}'
            ");

            if ($exists && (int) $exists->count > 0) {
                Schema::table('applicants', function (Blueprint $table) use ($dropper) {
                    $dropper($table);
                });
            }
        }
    }
};
