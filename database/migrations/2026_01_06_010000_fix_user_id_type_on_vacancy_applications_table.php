<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('vacancy_applications', 'user_id')) {
            return;
        }

        DB::statement('ALTER TABLE vacancy_applications MODIFY user_id BIGINT UNSIGNED NULL');

        $constraint = DB::selectOne("
            SELECT COUNT(*) AS count
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'vacancy_applications'
              AND COLUMN_NAME = 'user_id'
              AND REFERENCED_TABLE_NAME = 'users'
        ");

        if (! $constraint || (int) $constraint->count === 0) {
            DB::statement('ALTER TABLE vacancy_applications ADD CONSTRAINT vacancy_applications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        }
    }

    public function down(): void
    {
        $constraint = DB::selectOne("
            SELECT CONSTRAINT_NAME AS name
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'vacancy_applications'
              AND COLUMN_NAME = 'user_id'
              AND REFERENCED_TABLE_NAME = 'users'
            LIMIT 1
        ");

        if ($constraint && $constraint->name) {
            DB::statement('ALTER TABLE vacancy_applications DROP FOREIGN KEY ' . $constraint->name);
        }

        if (Schema::hasColumn('vacancy_applications', 'user_id')) {
            DB::statement('ALTER TABLE vacancy_applications MODIFY user_id CHAR(36) NULL');
        }
    }
};
