<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vacancy_applications')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $column = DB::selectOne("SHOW COLUMNS FROM vacancy_applications WHERE Field = 'status'");
        if (! $column || ! isset($column->Type)) {
            return;
        }

        if (! str_starts_with($column->Type, 'enum(')) {
            return;
        }

        if (! preg_match('/^enum\\((.*)\\)$/', $column->Type, $matches)) {
            return;
        }

        $values = str_getcsv($matches[1], ',', "'");
        if (in_array('withdrawn', $values, true)) {
            return;
        }

        $values[] = 'withdrawn';
        $enum = implode(',', array_map(fn ($value) => "'" . $value . "'", $values));

        DB::statement("ALTER TABLE vacancy_applications MODIFY status ENUM($enum) NOT NULL DEFAULT 'submitted'");
    }

    public function down(): void
    {
        if (! Schema::hasTable('vacancy_applications')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $column = DB::selectOne("SHOW COLUMNS FROM vacancy_applications WHERE Field = 'status'");
        if (! $column || ! isset($column->Type)) {
            return;
        }

        if (! str_starts_with($column->Type, 'enum(')) {
            return;
        }

        if (! preg_match('/^enum\\((.*)\\)$/', $column->Type, $matches)) {
            return;
        }

        $values = str_getcsv($matches[1], ',', "'");
        $values = array_values(array_filter($values, fn ($value) => $value !== 'withdrawn'));

        if ($values === []) {
            return;
        }

        $enum = implode(',', array_map(fn ($value) => "'" . $value . "'", $values));

        DB::statement("ALTER TABLE vacancy_applications MODIFY status ENUM($enum) NOT NULL DEFAULT 'submitted'");
    }
};
