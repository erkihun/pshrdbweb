<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('vacancy_applications', 'disability_type')) {
            Schema::table('vacancy_applications', function (Blueprint $table) {
                $table->string('disability_type')->nullable()->after('disability_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('vacancy_applications', 'disability_type')) {
            Schema::table('vacancy_applications', function (Blueprint $table) {
                $table->dropColumn('disability_type');
            });
        }
    }
};
