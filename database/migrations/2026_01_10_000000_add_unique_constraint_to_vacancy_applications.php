<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('vacancy_applications', 'applicant_id')) {
            return;
        }

        $hasDuplicates = DB::table('vacancy_applications')
            ->select('applicant_id')
            ->whereNotNull('applicant_id')
            ->groupBy('applicant_id')
            ->havingRaw('COUNT(*) > 1')
            ->limit(1)
            ->exists();

        if ($hasDuplicates) {
            return;
        }

        $index = DB::selectOne("
            SELECT COUNT(1) AS count
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'vacancy_applications'
              AND index_name = 'vacancy_applications_applicant_id_unique'
        ");

        if ($index && (int) $index->count > 0) {
            return;
        }

        Schema::table('vacancy_applications', function (Blueprint $table) {
            $table->unique('applicant_id');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('vacancy_applications', 'applicant_id')) {
            return;
        }

        $index = DB::selectOne("
            SELECT COUNT(1) AS count
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'vacancy_applications'
              AND index_name = 'vacancy_applications_applicant_id_unique'
        ");

        if (! $index || (int) $index->count === 0) {
            return;
        }

        Schema::table('vacancy_applications', function (Blueprint $table) {
            $table->dropUnique('vacancy_applications_applicant_id_unique');
        });
    }
};
