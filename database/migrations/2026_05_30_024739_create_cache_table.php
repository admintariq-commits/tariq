<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP TABLE IF EXISTS cache_locks CASCADE');
        DB::statement('DROP TABLE IF EXISTS cache CASCADE');

        DB::statement(<<<'SQL'
CREATE TABLE cache (
  "key" VARCHAR(255) PRIMARY KEY,
  "value" TEXT NOT NULL,
  "expiration" BIGINT NOT NULL
);
SQL
        );

        DB::statement(<<<'SQL'
CREATE TABLE cache_locks (
  "key" VARCHAR(255) PRIMARY KEY,
  "owner" VARCHAR(255) NOT NULL,
  "expiration" BIGINT NOT NULL
);
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
