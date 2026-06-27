<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\OtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OtpProviderTest extends TestCase
{
    public function test_nextsms_provider_can_send_otp(): void
    {
        Http::fake([
            'https://messaging-service.co.tz/api/sms/v1/text/single' => Http::response([
                'status' => 'success',
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'nextsms');
        config()->set('otp.nextsms_username', 'test-nextsms-user');
        config()->set('otp.nextsms_password', 'test-nextsms-pass');
        config()->set('otp.dev_fallback', false);

        $controller = new OtpController();
        $request = new Request(['phone' => '255682111222']);

        $response = app()->call([$controller, 'send'], ['request' => $request]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('"status":"ok"', $response->getContent());
        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            $data = $request->data();
            return $request->url() === 'https://messaging-service.co.tz/api/sms/v1/text/single'
                && $request->header('Authorization')[0] === 'Basic ' . base64_encode('test-nextsms-user:test-nextsms-pass')
                && ($data['from'] ?? null) === 'UniMessage'
                && ($data['to'] ?? null) === '255682111222'
                && str_contains($data['text'] ?? '', 'verification code');
        });
    }

    public function test_nextsms_sender_id_config_is_used(): void
    {
        Http::fake([
            'https://messaging-service.co.tz/api/sms/v1/text/single' => Http::response([
                'status' => 'success',
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'nextsms');
        config()->set('otp.nextsms_username', 'test-nextsms-user');
        config()->set('otp.nextsms_password', 'test-nextsms-pass');
        config()->set('otp.nextsms_sender', 'UniMessageID');
        config()->set('otp.dev_fallback', false);

        $controller = new OtpController();
        $request = new Request(['phone' => '255682111222']);

        $response = app()->call([$controller, 'send'], ['request' => $request]);

        $this->assertSame(200, $response->getStatusCode());
        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            $data = $request->data();
            return ($data['from'] ?? null) === 'UniMessageID'
                && ($data['to'] ?? null) === '255682111222';
        });
    }

    public function test_configured_test_phone_uses_dev_fallback_without_sending_sms(): void
    {
        Http::fake([
            'https://messaging-service.co.tz/api/sms/v1/text/single' => Http::response([
                'status' => 'success',
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'nextsms');
        config()->set('otp.nextsms_username', 'test-nextsms-user');
        config()->set('otp.nextsms_password', 'test-nextsms-pass');
        config()->set('otp.dev_fallback', false);
        config()->set('otp.test_phones', ['255626522599']);

        $controller = new OtpController();
        $request = new Request(['phone' => '255626522599']);

        $response = app()->call([$controller, 'send'], ['request' => $request]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('"status":"ok"', $response->getContent());
        $this->assertStringContainsString('"dev":true', $response->getContent());
        Http::assertNothingSent();
    }

    public function test_comma_separated_test_phones_use_dev_fallback_without_sending_sms(): void
    {
        Http::fake([
            'https://messaging-service.co.tz/api/sms/v1/text/single' => Http::response([
                'status' => 'success',
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'nextsms');
        config()->set('otp.nextsms_username', 'test-nextsms-user');
        config()->set('otp.nextsms_password', 'test-nextsms-pass');
        config()->set('otp.dev_fallback', false);
        config()->set('otp.test_phones', ['255626522599', '255712345678']);

        $controller = new OtpController();
        $request = new Request(['phone' => '255712345678']);

        $response = app()->call([$controller, 'send'], ['request' => $request]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('"status":"ok"', $response->getContent());
        $this->assertStringContainsString('"dev":true', $response->getContent());
        Http::assertNothingSent();
    }

}
