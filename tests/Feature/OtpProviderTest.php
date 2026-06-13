<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\OtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OtpProviderTest extends TestCase
{
    public function test_beem_provider_decodes_base64_secret_before_authentication(): void
    {
        Http::fake([
            'https://apiotp.beem.africa/v1/request' => Http::response([
                'status' => 'success',
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'beem');
        config()->set('otp.beem_api_key', 'test-beem-key');
        config()->set('otp.beem_api_secret', base64_encode('real-beem-secret'));
        config()->set('otp.beem_base_url', 'https://apiotp.beem.africa/v1/request');
        config()->set('otp.beem_app_id', '1');
        config()->set('otp.beem_sender', 'TARIQ');
        config()->set('otp.dev_fallback', false);

        $controller = new OtpController();
        $request = new Request(['phone' => '255682111222']);

        $response = app()->call([$controller, 'send'], ['request' => $request]);

        $this->assertSame(200, $response->getStatusCode());
        Http::assertSent(function ($request) {
            return $request->header('Authorization')[0] === 'Basic ' . base64_encode('test-beem-key:real-beem-secret');
        });
    }

    public function test_beem_provider_can_send_otp(): void
    {
        Http::fake([
            'https://apiotp.beem.africa/v1/request' => Http::response([
                'status' => 'success',
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'beem');
        config()->set('otp.beem_api_key', 'test-beem-key');
        config()->set('otp.beem_api_secret', 'test-beem-secret');
        config()->set('otp.beem_base_url', 'https://apiotp.beem.africa/v1/request');
        config()->set('otp.beem_app_id', '1');
        config()->set('otp.beem_sender', 'TARIQ');
        config()->set('otp.dev_fallback', false);

        $controller = new OtpController();
        $request = new Request(['phone' => '255682111222']);

        $response = app()->call([$controller, 'send'], ['request' => $request]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('"status":"ok"', $response->getContent());
        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://apiotp.beem.africa/v1/request'
                && $request->header('Authorization')[0] === 'Basic ' . base64_encode('test-beem-key:test-beem-secret')
                && $request['appId'] === '1'
                && $request['msisdn'] === '255682111222';
        });
    }

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
            return $request->url() === 'https://messaging-service.co.tz/api/sms/v1/text/single'
                && $request->header('Authorization')[0] === 'Basic ' . base64_encode('test-nextsms-user:test-nextsms-pass')
                && $request['from'] === 'NEXTSMS'
                && $request['to'] === '255682111222'
                && str_contains($request['text'], 'verification code');
        });
    }

    public function test_infobip_provider_can_send_otp(): void
    {
        Http::fake([
            'https://api.infobip.com/sms/2/text/advanced' => Http::response([
                'messages' => [
                    ['status' => ['groupId' => 1, 'groupName' => 'PENDING']],
                ],
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'infobip');
        config()->set('otp.infobip_api_key', 'test-infobip-key');
        config()->set('otp.infobip_base_url', 'https://api.infobip.com');
        config()->set('otp.infobip_from', 'TARIQ');
        config()->set('otp.dev_fallback', false);

        $controller = new OtpController();
        $request = new Request(['phone' => '255682111222']);

        $response = app()->call([$controller, 'send'], ['request' => $request]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('"status":"ok"', $response->getContent());
        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.infobip.com/sms/2/text/advanced'
                && $request->header('Authorization')[0] === 'App test-infobip-key'
                && $request['messages'][0]['from'] === 'TARIQ'
                && $request['messages'][0]['destinations'][0]['to'] === '255682111222';
        });
    }
}
