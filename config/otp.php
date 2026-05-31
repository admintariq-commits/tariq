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
    // Developer fallback: if true, return codes in API responses
    'dev_fallback' => env('OTP_DEV', false),
    // Email alerting for abuse attempts
    'alert_enabled' => env('OTP_ALERT_ENABLED', true),
    'alert_threshold' => env('OTP_ALERT_THRESHOLD', 3), // Send alert after N blocked send attempts or N failed verify attempts
    'alert_email' => env('ADMIN_EMAIL', 'admin@tariq.local'),
];
