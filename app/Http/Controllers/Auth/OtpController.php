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
    private function normalizeBeemSecret($secret)
    {
        if (!is_string($secret) || trim($secret) === '') {
            return $secret;
        }

        $candidate = trim($secret);
        if (!preg_match('/^[A-Za-z0-9+\/]+={0,2}$/', $candidate) || (strlen($candidate) % 4) !== 0) {
            return $secret;
        }

        $decoded = base64_decode($candidate, true);
        if ($decoded === false) {
            return $secret;
        }

        return base64_encode($decoded) === $candidate ? $decoded : $secret;
    }

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

        $provider = config('otp.sms_provider', env('SMS_PROVIDER', 'twilio'));
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioFrom = env('TWILIO_FROM');
        $infobipApiKey = config('otp.infobip_api_key', env('INFOBIP_API_KEY'));
        $infobipBaseUrl = config('otp.infobip_base_url', env('INFOBIP_BASE_URL', 'https://api.infobip.com'));
        $infobipFrom = config('otp.infobip_from', env('INFOBIP_FROM'));
        $beemApiKey = config('otp.beem_api_key', env('BEEM_API_KEY'));
        $beemApiSecret = $this->normalizeBeemSecret(config('otp.beem_api_secret', env('BEEM_SECRET_KEY', env('BEEM_API_SECRET'))));
        $beemBaseUrl = config('otp.beem_base_url', env('BEEM_BASE_URL', 'https://apiotp.beem.africa/v1/request'));
        $beemAppId = config('otp.beem_app_id', env('BEEM_APP_ID', '1'));
        $beemSender = config('otp.beem_sender', env('BEEM_SENDER', 'TARIQ'));
        $otpDev = config('otp.dev_fallback', false);

        if ($provider === 'infobip' && $infobipApiKey && $infobipBaseUrl && $infobipFrom) {
            try {
                $payload = [
                    'messages' => [
                        [
                            'from' => $infobipFrom,
                            'destinations' => [ [ 'to' => $phone ] ],
                            'text' => "Your TARIQ verification code is: {$code}",
                        ]
                    ]
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'App ' . $infobipApiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post(rtrim($infobipBaseUrl, '/') . '/sms/2/text/advanced', $payload);

                if ($response->successful()) {
                    Log::info('Infobip send response', ['phone' => $phone, 'resp' => $response->body()]);
                    return response()->json(['status' => 'ok']);
                }

                Log::warning('Infobip send failed', ['phone' => $phone, 'resp' => $response->body()]);
                return response()->json(['status' => 'error', 'message' => 'SMS provider error'], 500);
            } catch (Exception $e) {
                Log::error('Infobip exception', ['phone' => $phone, 'error' => $e->getMessage()]);
                return response()->json(['status' => 'error', 'message' => 'SMS provider exception'], 500);
            }
        }

        if ($provider === 'beem' && $beemApiKey && $beemApiSecret) {
            try {
                $payload = [
                    'appId' => (string) $beemAppId,
                    'msisdn' => $phone,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($beemApiKey . ':' . $beemApiSecret),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post(rtrim($beemBaseUrl, '/'), $payload);

                if ($response->successful()) {
                    Log::info('Beem send response', ['phone' => $phone, 'resp' => $response->body()]);
                    return response()->json(['status' => 'ok']);
                }

                Log::warning('Beem send failed', ['phone' => $phone, 'resp' => $response->body()]);
                return response()->json(['status' => 'error', 'message' => 'SMS provider error'], 500);
            } catch (Exception $e) {
                Log::error('Beem exception', ['phone' => $phone, 'error' => $e->getMessage()]);
                return response()->json(['status' => 'error', 'message' => 'SMS provider exception'], 500);
            }
        }

        if ($twilioSid && $twilioToken && $twilioFrom) {
            try {
                $body = "Your TARIQ verification code is: {$code}";
                $response = Http::withBasicAuth($twilioSid, $twilioToken)
                    ->asForm()
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$twilioSid}/Messages.json", [
                        'From' => $twilioFrom,
                        'To' => $phone,
                        'Body' => $body,
                    ]);
                if ($response->successful()) {
                    return response()->json(['status' => 'ok']);
                }
                Log::warning('Twilio send failed', ['resp' => $response->body()]);
                return response()->json(['status' => 'error', 'message' => 'SMS provider error'], 500);
            } catch (Exception $e) {
                Log::error('Twilio exception', ['error' => $e->getMessage()]);
                return response()->json(['status' => 'error', 'message' => 'SMS provider exception'], 500);
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
