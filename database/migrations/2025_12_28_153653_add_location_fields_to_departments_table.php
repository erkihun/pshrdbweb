<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('building_name')->nullable()->after('slug');
            $table->string('floor_number', 50)->nullable()->after('building_name');
            $table->enum('side', ['left', 'right', 'center'])->nullable()->after('floor_number');
            $table->string('office_room', 50)->nullable()->after('side');
            $table->text('department_address_note_am')->nullable()->after('office_room');
            $table->text('department_address_note_en')->nullable()->after('department_address_note_am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn([
                'building_name',
                'floor_number',
                'side',
                'office_room',
                'department_address_note_am',
                'department_address_note_en',
            ]);
        });
    }
};
