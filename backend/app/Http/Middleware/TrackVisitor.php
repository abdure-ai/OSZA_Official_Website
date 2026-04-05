<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Paths that should never be tracked.
     */
    protected array $exclude = [
        '/admin',
        '/up',
        '/livewire',
        '/_ignition',
        '/favicon.ico',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests on public pages
        if ($request->isMethod('GET') && !$this->shouldExclude($request)) {
            $this->record($request);
        }

        return $response;
    }

    protected function shouldExclude(Request $request): bool
    {
        $path = '/' . ltrim($request->path(), '/');
        foreach ($this->exclude as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }
        return false;
    }

    protected function record(Request $request): void
    {
        try {
            $ua = $request->userAgent() ?? '';
            $sessionId = $request->session()->getId();
            $page = '/' . ltrim($request->path(), '/');
            $ip = $request->ip();

            // Deduplicate: one entry per session+page per hour
            $hourBucket = floor(time() / 3600);
            $dedupKey = 'visit_' . md5($sessionId . $page . $hourBucket);

            if ($request->session()->has($dedupKey)) {
                return;
            }
            $request->session()->put($dedupKey, true);

            // Fetch Geolocation (Skip for local IPs)
            $locationData = [];
            if ($ip && !in_array($ip, ['127.0.0.1', '::1'])) {
                try {
                    $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city");
                    if ($response->successful() && $response->json('status') === 'success') {
                        $locationData = [
                            'country' => $response->json('country'),
                            'country_code' => $response->json('countryCode'),
                            'city' => $response->json('city'),
                            'region' => $response->json('regionName'),
                        ];
                    }
                } catch (\Exception $e) {
                    // Silently fail geolocation without breaking the log
                }
            }

            VisitorLog::create(array_merge([
                'ip_address' => $ip,
                'user_agent' => substr($ua, 0, 500),
                'page' => substr($page, 0, 512),
                'referrer' => substr((string) $request->headers->get('referer', ''), 0, 512),
                'device' => $this->detectDevice($ua),
                'browser' => $this->detectBrowser($ua),
                'session_id' => $sessionId,
                'locale' => session('locale', 'en'),
                'visited_at' => now(),
            ], $locationData));
        } catch (\Throwable) {
            // Never let tracking errors affect the user experience
        }
    }

    protected function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }
        if (
            str_contains($ua, 'mobile') ||
            str_contains($ua, 'android') ||
            str_contains($ua, 'iphone') ||
            str_contains($ua, 'ipod') ||
            str_contains($ua, 'blackberry') ||
            str_contains($ua, 'windows phone')
        ) {
            return 'mobile';
        }
        return 'desktop';
    }

    protected function detectBrowser(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'edg/') || str_contains($ua, 'edge/'))
            return 'Edge';
        if (str_contains($ua, 'opr/') || str_contains($ua, 'opera'))
            return 'Opera';
        if (str_contains($ua, 'chrome') && !str_contains($ua, 'chromium'))
            return 'Chrome';
        if (str_contains($ua, 'firefox'))
            return 'Firefox';
        if (str_contains($ua, 'safari') && !str_contains($ua, 'chrome'))
            return 'Safari';
        if (str_contains($ua, 'msie') || str_contains($ua, 'trident'))
            return 'IE';
        return 'Other';
    }
}
