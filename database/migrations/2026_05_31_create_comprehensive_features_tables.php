<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['employment_trends', 'salary_analysis', 'skills_gap', 'regional_analysis', 'custom']);
            $table->text('description')->nullable();
            $table->json('data')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('generated_at');
            $table->json('filters')->nullable();
            $table->boolean('is_public')->default(false);
            $table->json('scheduled_email_recipients')->nullable();
            $table->timestamps();
            $table->index('type');
            $table->index('generated_at');
        });

        Schema::create('job_market_trends', function (Blueprint $table) {
            $table->id();
            $table->string('industry');
            $table->string('job_title');
            $table->string('region')->nullable();
            $table->enum('demand_level', ['high', 'medium', 'low'])->default('medium');
            $table->decimal('average_salary', 10, 2)->nullable();
            $table->decimal('salary_range_min', 10, 2)->nullable();
            $table->decimal('salary_range_max', 10, 2)->nullable();
            $table->json('required_skills')->nullable();
            $table->string('experience_level')->nullable();
            $table->integer('vacancy_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->boolean('trending_up')->default(false);
            $table->float('trend_percentage')->default(0);
            $table->string('data_source')->nullable();
            $table->timestamp('last_updated');
            $table->timestamps();
            $table->index('job_title');
            $table->index('industry');
            $table->index('demand_level');
        });

        Schema::create('career_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['guide', 'course', 'video', 'article', 'tool']);
            $table->enum('category', ['interview_prep', 'skill_development', 'career_planning', 'professional_development']);
            $table->string('url');
            $table->string('provider')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->boolean('is_free')->default(true);
            $table->decimal('cost', 8, 2)->nullable();
            $table->float('rating')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_featured')->default(false);
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->index('type');
            $table->index('category');
            $table->index('is_featured');
        });

        Schema::create('career_resource_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('graduate_id')->constrained('graduates')->onDelete('cascade');
            $table->foreignId('resource_id')->constrained('career_resources')->onDelete('cascade');
            $table->timestamp('accessed_at')->nullable();
            $table->boolean('completed')->default(false);
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
            $table->unique(['graduate_id', 'resource_id']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // created, updated, deleted, verified, exported
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('timestamp');
            $table->timestamps();
            $table->index('model_type');
            $table->index('model_id');
            $table->index('action');
            $table->index('timestamp');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['job_match', 'unemployment_alert', 'verification_complete', 'verification_needed', 'system', 'alert']);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('action_url')->nullable();
            $table->string('icon')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('is_read');
            $table->index('type');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('career_resource_access');
        Schema::dropIfExists('career_resources');
        Schema::dropIfExists('job_market_trends');
        Schema::dropIfExists('analytics_reports');
    }
};
