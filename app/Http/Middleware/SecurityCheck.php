<?php

namespace App\Http\Middleware;

use App\Services\Security\BotDetectionService;
use App\Services\Security\VPNDetectionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check registration and login routes
        if (!$this->shouldCheck($request)) {
            return $next($request);
        }
        
        $botDetection = new BotDetectionService();
        $vpnDetection = new VPNDetectionService();
        
        // Calculate bot score
        $botScore = $botDetection->calculateBotScore($request);
        
        // Detect VPN
        $vpnInfo = $vpnDetection->detect($request);
        
        // Store in request for later use
        $request->merge([
            'bot_score' => $botScore,
            'vpn_detected' => $vpnInfo['vpn_detected'],
            'proxy_detected' => $vpnInfo['proxy_detected'],
            'security_info' => [
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('user-agent'),
                'bot_score' => $botScore,
                'vpn_detected' => $vpnInfo['vpn_detected'],
                'proxy_detected' => $vpnInfo['proxy_detected'],
                'datacenter_detected' => $vpnInfo['datacenter_detected'] ?? false,
            ]
        ]);
        
        // Flag suspicious activity
        $isSuspicious = false;
        $flags = [];
        
        if ($botDetection->isSuspectedBot($botScore, 0.65)) {
            $isSuspicious = true;
            $flags[] = 'suspected_bot';
        }
        
        if ($vpnInfo['vpn_detected']) {
            $flags[] = 'vpn_detected';
            // Can be flagged as suspicious depending on your policy
            // $isSuspicious = true;
        }
        
        if ($vpnInfo['proxy_detected']) {
            $flags[] = 'proxy_detected';
        }
        
        if ($vpnInfo['datacenter_detected']) {
            $flags[] = 'datacenter_detected';
        }
        
        // Store flags for later
        $request->merge(['security_flags' => $flags, 'is_suspicious' => $isSuspicious]);
        
        // Block if highly suspicious (suspected bot)
        if ($isSuspicious) {
            return response()->json([
                'error' => 'Suspicious activity detected. Please try again later or contact support.',
                'security_code' => 'SUSPICIOUS_ACTIVITY'
            ], 403);
        }
        
        return $next($request);
    }
    
    /**
     * Determine if this request should be security checked
     */
    private function shouldCheck(Request $request): bool
    {
        $routePath = $request->path();
        
        // Routes to check
        $checkRoutes = [
            'register',
            'graduate/register',
            'login',
            'admin/login',
        ];
        
        foreach ($checkRoutes as $route) {
            if (strpos($routePath, $route) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
