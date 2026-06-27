<?php
return [
    // Number of OTP sends allowed within the window
    'max_sends' => env('OTP_MAX_SENDS', 3),
    // Window (seconds) during which sends are counted
    'send_window_seconds' => env('OTP_SEND_WINDOW_SECONDS', 15 * 60),
    // Cooldown (seconds) applied after exceeding sends
    'cooldown_seconds' => env('OTP_COOLDOWN_SECONDS', 15 * 60),
    // OTP Time To Live in minutes
    'otp_ttl_minutes' => env('OTP_TTL_MINUTES', 10),
    // Max verification attempts before lockout
    'max_attempts' => env('OTP_MAX_ATTEMPTS', 5),
    // Attempt counter TTL in minutes
    'attempt_ttl_minutes' => env('OTP_ATTEMPT_TTL_MINUTES', 30),
    // SMS provider to use for OTP delivery
    'sms_provider' => env('SMS_PROVIDER', 'nextsms'),
    // NextSMS provider configuration (kept as the default provider)
    'nextsms_username' => env('NEXTSMS_USERNAME'),
    'nextsms_password' => env('NEXTSMS_PASSWORD'),
    'nextsms_api_token' => env('NEXTSMS_API_TOKEN'),
    'nextsms_base_url' => env('NEXTSMS_BASE_URL', 'https://messaging-service.co.tz/api/sms/v1/text/single'),
    'nextsms_sender' => env('NEXTSMS_SENDER', 'UniMessage'),

    // Developer fallback: if true, return codes in API responses (useful for testing)
    'dev_fallback' => env('OTP_DEV', 'false') === 'true',
    // Optional comma-separated test phones that should always use dev fallback for safe hosted testing
    'test_phones' => array_values(array_filter(array_map(static function ($value) {
        $value = trim((string) $value);
        return $value !== '' ? $value : null;
    }, explode(',', env('OTP_TEST_PHONES', env('OTP_TEST_PHONE', '')))), static function ($value) {
        return $value !== null;
    })),
    // Email alerting for abuse attempts
    'alert_enabled' => env('OTP_ALERT_ENABLED', true),
    'alert_threshold' => env('OTP_ALERT_THRESHOLD', 3), // Send alert after N blocked send attempts or N failed verify attempts
    'alert_email' => env('ADMIN_EMAIL', 'admin@tariq.local'),
];
