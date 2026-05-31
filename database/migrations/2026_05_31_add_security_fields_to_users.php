<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('password');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->float('bot_score')->default(0)->after('user_agent'); // 0-1 score
            $table->boolean('vpn_detected')->default(false)->after('bot_score');
            $table->boolean('is_suspicious')->default(false)->after('vpn_detected');
            $table->json('security_flags')->nullable()->after('is_suspicious');
            $table->timestamp('last_login_at')->nullable()->after('security_flags');
            $table->timestamp('last_suspicious_activity_at')->nullable()->after('last_login_at');
            
            $table->index('bot_score');
            $table->index('vpn_detected');
            $table->index('is_suspicious');
        });

        Schema::table('graduates', function (Blueprint $table) {
            $table->string('document_hash')->nullable()->after('resume_path');
            $table->enum('document_verification_status', ['pending', 'verified', 'rejected', 'manual_review'])->default('pending')->after('document_hash');
            $table->timestamp('document_verified_at')->nullable()->after('document_verification_status');
            $table->string('document_verified_by')->nullable()->after('document_verified_at');
            
            $table->index('document_verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['bot_score']);
            $table->dropIndex(['vpn_detected']);
            $table->dropIndex(['is_suspicious']);
            $table->dropColumn([
                'ip_address',
                'user_agent',
                'bot_score',
                'vpn_detected',
                'is_suspicious',
                'security_flags',
                'last_login_at',
                'last_suspicious_activity_at'
            ]);
        });

        Schema::table('graduates', function (Blueprint $table) {
            $table->dropIndex(['document_verification_status']);
            $table->dropColumn([
                'document_hash',
                'document_verification_status',
                'document_verified_at',
                'document_verified_by'
            ]);
        });
    }
};
