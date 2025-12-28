<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('phone_primary')->nullable()->after('code');
            $table->string('phone_secondary')->nullable()->after('phone_primary');
            $table->string('email_primary')->nullable()->after('phone_secondary');
            $table->string('email_secondary')->nullable()->after('email_primary');
            $table->string('physical_address')->nullable()->after('email_secondary');
            $table->string('city')->default('Addis Ababa')->after('physical_address');
            $table->string('region')->nullable()->after('city');
            $table->string('country')->default('Ethiopia')->after('region');
            $table->text('map_embed_url')->nullable()->after('country');
            $table->string('website_url')->nullable()->after('map_embed_url');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'phone_primary',
                'phone_secondary',
                'email_primary',
                'email_secondary',
                'physical_address',
                'city',
                'region',
                'country',
                'map_embed_url',
                'website_url',
            ]);
        });
    }
};
