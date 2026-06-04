<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS URLs in production
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            if (Schema::hasTable('settings')) {
                $mailFromAddress = Setting::get('mail_from_address') ?? null;
                $mailFromName = Setting::get('mail_from_name') ?? null;
                $smtpHost = Setting::get('smtp_host') ?? null;
                $smtpPort = Setting::get('smtp_port') ?? null;
                $smtpEncryption = Setting::get('smtp_encryption') ?? null;
                $smtpUsername = Setting::get('smtp_username') ?? null;
                $smtpPassword = Setting::get('smtp_password') ?? null;
                $smtpTimeout = Setting::get('smtp_timeout') ?? null;
                $sessionTimeout = Setting::get('session_timeout_minutes') ?? null;

                if ($mailFromAddress) {
                    config(['mail.from.address' => $mailFromAddress]);
                }
                if ($mailFromName) {
                    config(['mail.from.name' => $mailFromName]);
                }
                if ($smtpHost) {
                    config(['mail.mailers.smtp.host' => $smtpHost]);
                }
                if ($smtpPort) {
                    config(['mail.mailers.smtp.port' => (int) $smtpPort]);
                }
                if ($smtpEncryption) {
                    config(['mail.mailers.smtp.encryption' => $smtpEncryption === 'none' ? null : $smtpEncryption]);
                }
                if ($smtpUsername) {
                    config(['mail.mailers.smtp.username' => $smtpUsername]);
                }
                if ($smtpPassword) {
                    config(['mail.mailers.smtp.password' => $smtpPassword]);
                }
                if ($smtpTimeout) {
                    config(['mail.mailers.smtp.timeout' => (int) $smtpTimeout]);
                }
                if ($sessionTimeout) {
                    config(['session.lifetime' => (int) $sessionTimeout]);
                }
            }
        } catch (\Throwable $e) {
            // Database not available or query failed
            // Silently skip loading settings from database
        }
    }
}
