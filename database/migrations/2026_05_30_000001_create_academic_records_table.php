<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('graduate_id')->nullable()->constrained('graduates')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('index_number')->unique();
            $table->string('registration_number')->nullable();
            $table->string('university')->nullable();
            $table->string('course')->nullable();
            $table->string('degree')->nullable();
            $table->year('graduation_year')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected', 'manual_review'])->default('pending');
            $table->enum('source', ['manual', 'ministry_api', 'university_api', 'cached'])->default('manual');
            $table->json('verification_data')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['status', 'source']);
            $table->index(['graduate_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};
