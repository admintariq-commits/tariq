<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class OtpController extends Controller
{
    // (Removed Beem/Infobip/Twilio helpers) OTP will use NextSMS as the only provider.

    /**
     * Send email alert for abuse attempts if threshold exceeded
     */
    private function sendAbuseAlert($phone, $type, $count)
    {
        if (!config('otp.alert_enabled')) return;
        
        $threshold = config('otp.alert_threshold', 3);
        if ($count < $threshold) return;

        $alertEmail = config('otp.alert_email');
        if (!$alertEmail) return;

        try {
            Mail::raw("OTP Abuse Alert:\n\nPhone: {$phone}\nType: {$type}\nAttempts: {$count}\nTime: " . now(), function ($msg) use ($alertEmail) {
                $msg->to($alertEmail)
                    ->subject('[TARIQ] OTP Abuse Alert - ' . $type);
            });
            Log::info('Abuse alert sent', ['phone' => $phone, 'type' => $type, 'attempts' => $count]);
        } catch (Exception $e) {
            Log::error('Failed to send abuse alert', ['error' => $e->getMessage()]);
        }
    }
    public function send(Request $request)
    {
        $request->validate([ 'phone' => 'required' ]);
        $phone = preg_replace('/\\s+/', '', $request->phone);

        // Basic per-phone send rate limiting (in addition to route throttle)
        $sendKey = 'otp:send_count:'.$phone;
        $cooldownKey = 'otp:cooldown:'.$phone;
        $maxSends = config('otp.max_sends', 3);
        $windowSeconds = config('otp.send_window_seconds', 15 * 60);
        $cooldownSeconds = config('otp.cooldown_seconds', $windowSeconds);

        // If cooldown active, return 429 with remaining seconds
        if (Cache::has($cooldownKey)) {
            $remaining = Cache::get($cooldownKey) - time();
            Log::warning('OTP send blocked by cooldown', ['phone'=>$phone, 'remaining'=>$remaining]);
            $this->sendAbuseAlert($phone, 'send_cooldown_active', 0);
            return response()->json(['status' => 'error', 'message' => 'Too many OTP requests. Try again in '.$remaining.' seconds.'], 429);
        }

        $count = Cache::get($sendKey, 0);
        $count = Cache::increment($sendKey);
        if ($count === 1) {
            // first increment, set expiration
            Cache::put($sendKey, 1, now()->addSeconds($windowSeconds));
        }
        if ($count > $maxSends) {
            // set cooldown to prevent further sends for cooldownSeconds
            Cache::put($cooldownKey, time() + $cooldownSeconds, $cooldownSeconds);
            Log::warning('OTP send limit exceeded, cooldown set', ['phone'=>$phone, 'cooldown'=>$cooldownSeconds]);
            $this->sendAbuseAlert($phone, 'send_limit_exceeded', $count);
            return response()->json(['status' => 'error', 'message' => 'Too many OTP requests. Try again in '.$cooldownSeconds.' seconds.'], 429);
        }

        $code = random_int(100000, 999999);
        // Store OTP in cache for configured TTL
        $otpTtl = config('otp.otp_ttl_minutes', 10);
        Cache::put('otp:'.$phone, $code, now()->addMinutes($otpTtl));

        // Use NextSMS as the only provider by default
        $provider = config('otp.sms_provider', env('SMS_PROVIDER', 'nextsms'));
        $nextsmsUsername = config('otp.nextsms_username', env('NEXTSMS_USERNAME'));
        $nextsmsPassword = config('otp.nextsms_password', env('NEXTSMS_PASSWORD'));
        $nextsmsBaseUrl = config('otp.nextsms_base_url', env('NEXTSMS_BASE_URL', 'https://messaging-service.co.tz/api/sms/v1/text/single'));
        $nextsmsSender = config('otp.nextsms_sender', env('NEXTSMS_SENDER', 'NEXTSMS'));
        $otpDev = config('otp.dev_fallback', false);

        // Only NextSMS provider is supported now
        if ($provider === 'nextsms' && $nextsmsUsername && $nextsmsPassword) {
            try {
                $to = $phone;
                if (!str_starts_with($to, '255')) {
                    $to = '255' . ltrim($to, '0');
                }

                $response = Http::withBasicAuth($nextsmsUsername, $nextsmsPassword)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->post($nextsmsBaseUrl, [
                        'from' => $nextsmsSender,
                        'to' => $to,
                        'text' => "Your TARIQ verification code is: {$code}",
                    ]);

                if ($response->successful()) {
                    Log::info('NextSMS send response', ['phone' => $phone, 'to' => $to, 'resp' => $response->body()]);
                    return response()->json(['status' => 'ok']);
                }

                $respBody = $response->body();
                Log::warning('NextSMS send failed', ['phone' => $phone, 'to' => $to, 'status' => $response->status(), 'resp' => $respBody]);

                $details = [
                    'provider' => 'nextsms',
                    'http_status' => $response->status(),
                    'response_body' => $respBody,
                    'request' => [
                        'from' => $nextsmsSender,
                        'to' => $to,
                        'text' => "Your TARIQ verification code is: {$code}",
                        'base_url' => $nextsmsBaseUrl,
                    ],
                ];

                // Always show details for debugging NextSMS issues
                return response()->json([
                    'status' => 'error',
                    'message' => 'NextSMS provider error (HTTP ' . $response->status() . ')',
                    'details' => $details,
                ], 500);
            } catch (Exception $e) {
                Log::error('NextSMS exception', ['phone' => $phone, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'NextSMS provider exception: ' . $e->getMessage(),
                    'details' => [
                        'error' => $e->getMessage(),
                        'type' => get_class($e),
                    ]
                ], 500);
            }
        }

        // If developer fallback is enabled explicitly, return code for local/dev testing
        if ($otpDev) {
            Log::info('OTP dev-fallback used for phone', ['phone'=>$phone, 'code'=>$code]);
            return response()->json(['status' => 'ok', 'code' => $code, 'dev' => true]);
        }

        // Otherwise do not expose codes and require provider configuration
        Log::warning('OTP send attempted but no provider configured', ['phone' => $phone]);
        return response()->json(['status' => 'error', 'message' => 'OTP provider not configured'], 422);
    }

    public function verify(Request $request)
    {
        $request->validate([ 'phone' => 'required', 'code' => 'required' ]);
        $phone = preg_replace('/\s+/', '', $request->phone);
        // Limit verification attempts per phone to prevent brute force
        $attemptsKey = 'otp:attempts:'.$phone;
        $maxAttempts = config('otp.max_attempts', 5);
        $attemptTtl = config('otp.attempt_ttl_minutes', 30);
        $attempts = Cache::get($attemptsKey, 0);
        if ($attempts >= $maxAttempts) {
            Log::warning('OTP verify blocked: too many attempts', ['phone'=>$phone, 'attempts'=>$attempts]);
            $this->sendAbuseAlert($phone, 'verify_attempts_exceeded', $attempts);
            return response()->json(['status' => 'error', 'message' => 'Too many verification attempts, please request a new code.'], 429);
        }

        $cached = Cache::get('otp:'.$phone);
        if (!$cached || (string)$cached !== (string)$request->code) {
            Cache::put($attemptsKey, $attempts + 1, now()->addMinutes($attemptTtl));
            Log::info('OTP verify invalid attempt', ['phone'=>$phone, 'attempts'=> $attempts + 1]);
            return response()->json(['status' => 'error', 'message' => 'Invalid code'], 422);
        }
        // mark verified in session for registration flow
        session(['otp_verified_'.$phone => true]);
        Cache::forget('otp:'.$phone);
        // clear attempts and send counters on success
        Cache::forget($attemptsKey);
        Cache::forget('otp:send_count:'.$phone);
        Cache::forget('otp:cooldown:'.$phone);
        Log::info('OTP verified successfully', ['phone'=>$phone]);
        return response()->json(['status' => 'ok']);
    }
}
