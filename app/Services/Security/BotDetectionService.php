<?php

namespace App\Services\Security;

use Illuminate\Http\Request;

class BotDetectionService
{
    /**
     * Calculate bot score (0-1, higher = more likely bot)
     */
    public function calculateBotScore(Request $request): float
    {
        $score = 0.0;
        $checks = [];
        
        // Check User-Agent
        $userAgentCheck = $this->checkUserAgent($request);
        $score += $userAgentCheck['score'] * 0.3; // 30% weight
        $checks['user_agent'] = $userAgentCheck;
        
        // Check request patterns
        $patternCheck = $this->checkRequestPatterns($request);
        $score += $patternCheck['score'] * 0.25; // 25% weight
        $checks['patterns'] = $patternCheck;
        
        // Check headers
        $headerCheck = $this->checkHeaders($request);
        $score += $headerCheck['score'] * 0.25; // 25% weight
        $checks['headers'] = $headerCheck;
        
        // Check form submission patterns
        $formCheck = $this->checkFormPatterns($request);
        $score += $formCheck['score'] * 0.2; // 20% weight
        $checks['form_patterns'] = $formCheck;
        
        // Store detailed checks
        \Cache::put('bot_check_' . $request->ip(), $checks, now()->addHours(24));
        
        return min(1.0, $score);
    }
    
    /**
     * Check User-Agent string
     */
    private function checkUserAgent(Request $request): array
    {
        $ua = $request->header('user-agent', '');
        $score = 0.0;
        $reasons = [];
        
        // Known bot signatures
        $botSignatures = [
            'bot' => 0.8,
            'crawler' => 0.8,
            'spider' => 0.8,
            'scraper' => 0.9,
            'selenium' => 0.95,
            'phantomjs' => 0.95,
            'headless' => 0.9,
            'automated' => 0.85,
            'curl' => 0.7,
            'wget' => 0.7,
            'python' => 0.6,
        ];
        
        $lowerUA = strtolower($ua);
        foreach ($botSignatures as $sig => $sigScore) {
            if (strpos($lowerUA, $sig) !== false) {
                $score = max($score, $sigScore);
                $reasons[] = "Bot signature detected: $sig";
            }
        }
        
        // Missing or suspicious User-Agent
        if (empty($ua)) {
            $score = 0.7;
            $reasons[] = "Missing user-agent";
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    /**
     * Check request patterns
     */
    private function checkRequestPatterns(Request $request): array
    {
        $score = 0.0;
        $reasons = [];
        
        // Suspicious HTTP methods
        if (!in_array($request->method(), ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'])) {
            $score += 0.3;
            $reasons[] = "Unusual HTTP method";
        }
        
        // Check accept headers
        $accept = $request->header('accept', '');
        if (empty($accept) || $accept === '*/*') {
            $score += 0.2;
            $reasons[] = "Suspicious accept header";
        }
        
        // Check referer
        $referer = $request->header('referer', '');
        if (empty($referer) && $request->method() === 'POST') {
            $score += 0.15;
            $reasons[] = "Missing referer on POST request";
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    /**
     * Check headers for suspicious patterns
     */
    private function checkHeaders(Request $request): array
    {
        $score = 0.0;
        $reasons = [];
        
        // Check for headless browser patterns
        if ($request->header('chrome-lighthouse') || 
            $request->header('sec-fetch-dest') === 'empty') {
            $score += 0.2;
            $reasons[] = "Possible automated tool detected";
        }
        
        // Check for missing common headers
        $commonHeaders = ['accept-language', 'accept-encoding'];
        $missingHeaders = 0;
        foreach ($commonHeaders as $header) {
            if (!$request->header($header)) {
                $missingHeaders++;
            }
        }
        if ($missingHeaders > 0) {
            $score += ($missingHeaders * 0.1);
            $reasons[] = "Missing common browser headers";
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    /**
     * Check form submission patterns
     */
    private function checkFormPatterns(Request $request): array
    {
        $score = 0.0;
        $reasons = [];
        
        // Filling forms too fast (less than 2 seconds)
        $sessionId = $request->getSession()->getId();
        $lastFormTime = \Cache::get('form_time_' . $sessionId);
        
        if ($lastFormTime && (time() - $lastFormTime < 2)) {
            $score += 0.3;
            $reasons[] = "Form filled suspiciously fast";
        }
        
        // Too many form fields filled automatically
        if ($request->isMethod('post') && count($request->all()) > 50) {
            $score += 0.2;
            $reasons[] = "Unusually large form submission";
        }
        
        // Cache this form time
        \Cache::put('form_time_' . $sessionId, time(), now()->addHours(24));
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    /**
     * Check if bot score threshold exceeded
     */
    public function isSuspectedBot(float $score, float $threshold = 0.6): bool
    {
        return $score >= $threshold;
    }
}
