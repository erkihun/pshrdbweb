<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_stat_snapshots', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('organization_id');
            $table->enum('period_type', ['monthly', 'quarterly', 'yearly']);
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedTinyInteger('quarter')->nullable();
            $table->unsignedTinyInteger('month')->nullable();
            $table->json('totals');
            $table->json('breakdown')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->cascadeOnDelete();

            $table->unique(['organization_id', 'period_type', 'year', 'quarter', 'month'], 'org_stat_snapshots_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_stat_snapshots');
    }
};
