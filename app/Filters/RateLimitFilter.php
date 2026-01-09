<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RateLimitFilter.php
 * Filter untuk rate limiting dan brute force prevention
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */
class RateLimitFilter implements FilterInterface
{
    /**
     * Rate limit configuration
     */
    private int $maxRequests = 120;      // Max requests per window (2x)
    private int $windowSeconds = 60;     // Time window in seconds
    private int $loginMaxAttempts = 10;  // Max login attempts (2x)
    private int $loginLockoutTime = 60;  // Lockout time (1 minute, reduced)

    /**
     * Before filter - check rate limits
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $ip = $request->getIPAddress();
        $path = $request->getUri()->getPath();
        $cache = \Config\Services::cache();
        
        // Special handling for login route
        if (strpos($path, '/login') !== false && $request->getMethod() === 'POST') {
            return $this->checkLoginRateLimit($ip, $cache);
        }
        
        // General rate limiting
        return $this->checkGeneralRateLimit($ip, $cache);
    }

    /**
     * After filter
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add rate limit headers
        $ip = $request->getIPAddress();
        $cache = \Config\Services::cache();
        $key = "rate_limit_{$ip}";
        $requests = $cache->get($key) ?? 0;
        $remaining = max(0, $this->maxRequests - $requests);
        
        return $response
            ->setHeader('X-RateLimit-Limit', (string)$this->maxRequests)
            ->setHeader('X-RateLimit-Remaining', (string)$remaining)
            ->setHeader('X-RateLimit-Reset', (string)(time() + $this->windowSeconds));
    }

    /**
     * Check general rate limit
     */
    private function checkGeneralRateLimit(string $ip, $cache)
    {
        $key = "rate_limit_{$ip}";
        $requests = $cache->get($key) ?? 0;
        
        if ($requests >= $this->maxRequests) {
            log_message('warning', "Rate limit exceeded for IP: {$ip}");
            return $this->tooManyRequests();
        }
        
        // Increment counter
        $cache->save($key, $requests + 1, $this->windowSeconds);
        
        return null;
    }

    /**
     * Check login rate limit (stricter)
     */
    private function checkLoginRateLimit(string $ip, $cache)
    {
        $key = "login_attempts_{$ip}";
        $lockKey = "login_lockout_{$ip}";
        
        // Check if currently locked out
        if ($cache->get($lockKey)) {
            $ttl = $cache->getMetaData($lockKey)['expire'] ?? 0;
            $remaining = max(0, $ttl - time());
            $minutes = ceil($remaining / 60);
            
            log_message('warning', "Login lockout active for IP: {$ip}");
            return service('response')
                ->setStatusCode(429)
                ->setJSON([
                    'error' => true,
                    'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$minutes} menit."
                ]);
        }
        
        $attempts = $cache->get($key) ?? 0;
        
        if ($attempts >= $this->loginMaxAttempts) {
            // Set lockout
            $cache->save($lockKey, true, $this->loginLockoutTime);
            $cache->delete($key);
            
            log_message('warning', "Login lockout triggered for IP: {$ip}");
            return service('response')
                ->setStatusCode(429)
                ->setJSON([
                    'error' => true,
                    'message' => 'Terlalu banyak percobaan login. Akun dikunci selama 15 menit.'
                ]);
        }
        
        return null;
    }

    /**
     * Return 429 Too Many Requests
     */
    private function tooManyRequests()
    {
        return service('response')
            ->setStatusCode(429)
            ->setHeader('Retry-After', (string)$this->windowSeconds)
            ->setBody('Too Many Requests. Please try again later.');
    }

    /**
     * Record failed login attempt (call from Auth controller)
     */
    public static function recordFailedLogin(string $ip): void
    {
        $cache = \Config\Services::cache();
        $key = "login_attempts_{$ip}";
        $attempts = $cache->get($key) ?? 0;
        $cache->save($key, $attempts + 1, 900); // 15 minutes
    }

    /**
     * Clear login attempts on successful login
     */
    public static function clearLoginAttempts(string $ip): void
    {
        $cache = \Config\Services::cache();
        $cache->delete("login_attempts_{$ip}");
        $cache->delete("login_lockout_{$ip}");
    }
}
