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
            'https://apisms.beem.africa/v1/send' => Http::response([
                'status' => 'success',
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'beem');
        config()->set('otp.beem_api_key', 'test-beem-key');
        config()->set('otp.beem_api_secret', base64_encode('real-beem-secret'));
        config()->set('otp.beem_base_url', 'https://apisms.beem.africa/v1/send');
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
            'https://apisms.beem.africa/v1/send' => Http::response([
                'status' => 'success',
            ], 200),
        ]);

        config()->set('otp.sms_provider', 'beem');
        config()->set('otp.beem_api_key', 'test-beem-key');
        config()->set('otp.beem_api_secret', 'test-beem-secret');
        config()->set('otp.beem_base_url', 'https://apisms.beem.africa/v1/send');
        config()->set('otp.beem_sender', 'TARIQ');
        config()->set('otp.dev_fallback', false);

        $controller = new OtpController();
        $request = new Request(['phone' => '255682111222']);

        $response = app()->call([$controller, 'send'], ['request' => $request]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('"status":"ok"', $response->getContent());
        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://apisms.beem.africa/v1/send'
                && $request->header('Authorization')[0] === 'Basic ' . base64_encode('test-beem-key:test-beem-secret')
                && $request['source_addr'] === 'TARIQ'
                && str_contains($request['message'], 'Your TARIQ verification code is:')
                && $request['recipients'][0]['dest_addr'] === '255682111222';
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
