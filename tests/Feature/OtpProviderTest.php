<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\OtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OtpProviderTest extends TestCase
{
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
                && $request['from'] === 'TARIQ'
                && $request['to'][0] === '255682111222';
        });
    }
}
