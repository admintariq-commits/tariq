<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('graduation_date');
            $table->foreignId('university_id')->constrained('universities');
            $table->foreignId('course_id')->constrained('courses');
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('phone');
            $table->text('skills')->nullable();
            $table->enum('employment_status', ['employed', 'self_employed', 'unemployed'])->default('unemployed');
            $table->date('last_employment_date')->nullable();
            $table->integer('months_unemployed')->default(0);
            $table->string('resume_path')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduates');
    }
};