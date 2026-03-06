<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE stay_connecteds MODIFY url TEXT NULL');
            DB::statement('ALTER TABLE stay_connecteds MODIFY embed_url TEXT NOT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE stay_connecteds MODIFY url VARCHAR(255) NULL');
            DB::statement('ALTER TABLE stay_connecteds MODIFY embed_url VARCHAR(255) NOT NULL');
        }
    }
};
