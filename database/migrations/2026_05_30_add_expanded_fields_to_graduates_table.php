<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            // Personal information
            $table->string('national_id')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            // Academic information (text fields as fallback)
            $table->string('university')->nullable();
            $table->string('course')->nullable();
            $table->enum('degree', ['diploma', 'bachelor', 'master', 'phd'])->nullable();
            $table->integer('graduation_year')->nullable();

            // Geographic & Career information
            $table->string('region')->nullable();
            $table->string('job_title')->nullable();
            $table->integer('expected_salary')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('linkedin')->nullable();

            // Skills & Languages
            $table->text('languages')->nullable();
            $table->text('certifications')->nullable();
            $table->text('job_preferences')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->dropColumn([
                'national_id', 'gender', 'university', 'course', 'degree', 'graduation_year',
                'region', 'job_title', 'expected_salary', 'experience_years', 'linkedin',
                'languages', 'certifications', 'job_preferences'
            ]);
        });
    }
};
