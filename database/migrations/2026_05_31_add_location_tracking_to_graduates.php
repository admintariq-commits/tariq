<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('detected_region')->nullable();
            $table->string('location_source')->nullable();
            $table->boolean('region_match')->default(true);
            $table->string('location_accuracy')->nullable();

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
