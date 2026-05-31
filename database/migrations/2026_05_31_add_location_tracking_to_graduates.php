<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('region');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('detected_region')->nullable()->after('longitude');
            $table->string('location_source')->nullable()->after('detected_region');
            $table->boolean('region_match')->default(true)->after('location_source');
            $table->string('location_accuracy')->nullable()->after('region_match');

            $table->index('detected_region');
            $table->index('region_match');
        });
    }

    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->dropIndex(['detected_region']);
            $table->dropIndex(['region_match']);
            $table->dropColumn(['latitude', 'longitude', 'detected_region', 'location_source', 'region_match', 'location_accuracy']);
        });
    }
};
