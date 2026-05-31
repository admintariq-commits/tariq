<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_market', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('required_skills');
            $table->decimal('min_gpa', 3, 2);
            $table->string('salary_range')->nullable();
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->foreignId('employer_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_market');
    }
};