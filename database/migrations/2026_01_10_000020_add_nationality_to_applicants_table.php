<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('applicants', 'nationality')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->string('nationality', 100)->after('gender');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('applicants', 'nationality')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->dropColumn('nationality');
            });
        }
    }
};
