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
            $table->string('national_id')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('national_id');

            // Academic information (text fields as fallback)
            $table->string('university')->nullable()->after('university_id');
            $table->string('course')->nullable()->after('course_id');
            $table->enum('degree', ['diploma', 'bachelor', 'master', 'phd'])->nullable()->after('gpa');
            $table->integer('graduation_year')->nullable()->after('graduation_date');

            // Geographic & Career information
            $table->string('region')->nullable()->after('employment_status');
            $table->string('job_title')->nullable()->after('region');
            $table->integer('expected_salary')->nullable()->after('job_title');
            $table->integer('experience_years')->nullable()->after('expected_salary');
            $table->string('linkedin')->nullable()->after('experience_years');

            // Skills & Languages
            $table->text('languages')->nullable()->after('skills');
            $table->text('certifications')->nullable()->after('languages');
            $table->text('job_preferences')->nullable()->after('certifications');
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
