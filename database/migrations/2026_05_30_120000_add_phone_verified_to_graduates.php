<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            if (!Schema::hasColumn('graduates', 'phone_verified')) {
                $table->boolean('phone_verified')->default(false)->after('resume_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            if (Schema::hasColumn('graduates', 'phone_verified')) {
                $table->dropColumn('phone_verified');
            }
        });
    }
};
