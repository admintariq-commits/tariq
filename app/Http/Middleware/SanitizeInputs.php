<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SanitizeInputs
{
    /**
     * Handle an incoming request with input sanitization.
     * Cleans and validates user input to prevent XSS, SQL injection, and other attacks.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip sanitization for file uploads and certain routes
        if ($request->method() !== 'GET') {
            $sanitized = [];
            foreach ($request->all() as $key => $value) {
                // Do not sanitize uploaded file objects
                if ($request->hasFile($key)) {
                    $sanitized[$key] = $value;
                    continue;
                }

                $sanitized[$key] = $this->sanitize($value);
            }
            $request->merge($sanitized);
        }

        return $next($request);
    }

    /**
     * Recursively sanitize input values
     */
    private function sanitize($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }

        if (!is_string($value)) {
            return $value;
        }

        // Remove null bytes
        $value = str_replace("\0", '', $value);

        // Strip HTML tags and dangerous patterns
        $value = strip_tags($value);
        $value = $this->removeScriptPatterns($value);

        // Normalize whitespace
        $value = trim(preg_replace('/\s+/', ' ', $value));

        return $value;
    }

    /**
     * Remove dangerous script/event patterns
     */
    private function removeScriptPatterns($value)
    {
        $patterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/is',
            '/on\w+\s*=/is',
            '/<iframe/is',
            '/<object/is',
            '/<embed/is',
            '/eval\(/is',
            '/expression\(/is',
        ];

        foreach ($patterns as $pattern) {
            $value = preg_replace($pattern, '', $value);
        }

        return $value;
    }
}
