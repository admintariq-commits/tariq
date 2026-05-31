<?php

namespace App\Services\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VPNDetectionService
{
    /**
     * Detect if request is coming from VPN/Proxy
     */
    public function detect(Request $request): array
    {
        $ip = $this->getClientIP($request);
        
        $results = [
            'vpn_detected' => false,
            'proxy_detected' => false,
            'tor_detected' => false,
            'datacenter_detected' => false,
            'provider' => null,
            'country' => null,
            'confidence' => 0,
        ];
        
        // Check headers for VPN/proxy indicators
        $headers = $this->checkProxyHeaders($request);
        if ($headers['detected']) {
            $results['proxy_detected'] = true;
            $results['confidence'] += 30;
        }
        
        // Check IP reputation via local database
        $ipCheck = $this->checkIPReputation($ip);
        if ($ipCheck['is_vpn']) {
            $results['vpn_detected'] = true;
            $results['confidence'] += 40;
        }
        if ($ipCheck['is_proxy']) {
            $results['proxy_detected'] = true;
            $results['confidence'] += 35;
        }
        if ($ipCheck['is_datacenter']) {
            $results['datacenter_detected'] = true;
            $results['confidence'] += 25;
        }
        
        $results['country'] = $ipCheck['country'] ?? null;
        $results['provider'] = $ipCheck['provider'] ?? null;
        
        return $results;
    }
    
    /**
     * Get client IP address
     */
    private function getClientIP(Request $request): string
    {
        $ip = $request->ip();
        
        // Check for forwarded IPs
        if ($request->has('x-forwarded-for')) {
            $ips = explode(',', $request->header('x-forwarded-for'));
            $ip = trim($ips[0]);
        }
        
        return $ip;
    }
    
    /**
     * Check for proxy headers
     */
    private function checkProxyHeaders(Request $request): array
    {
        $proxyHeaders = [
            'HTTP_VIA',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED_HOST',
            'HTTP_X_FORWARDED_PROTO',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_FORWARDED',
            'HTTP_X_PROXY_AUTHORIZATION',
            'HTTP_X_PROXY_CONNECTION',
        ];
        
        $detected = false;
        foreach ($proxyHeaders as $header) {
            if ($request->header($header)) {
                $detected = true;
                break;
            }
        }
        
        return ['detected' => $detected];
    }
    
    /**
     * Check IP reputation (simplified - in production use API)
     */
    private function checkIPReputation(string $ip): array
    {
        // This is a simplified version - in production you'd use:
        // - MaxMind GeoIP2
        // - IP2Location
        // - IPQualityScore
        // - abuseipdb.com
        
        $vpsProviders = [
            '45.33', '45.55', '45.56', // Linode
            '172.64', '172.65', '172.66', // Cloudflare
            '34.64', '34.65', '34.66', // Google Cloud
            '13.32', '13.33', '13.34', // AWS
            '104.19', '104.20', '104.21', // Vultr
        ];
        
        $isDatacenter = false;
        foreach ($vpsProviders as $provider) {
            if (strpos($ip, $provider) === 0) {
                $isDatacenter = true;
                break;
            }
        }
        
        return [
            'is_vpn' => false,
            'is_proxy' => false,
            'is_datacenter' => $isDatacenter,
            'provider' => null,
            'country' => null,
        ];
    }
}
