<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * SecurityFilter.php
 * Filter untuk sanitasi input, XSS prevention, dan script injection blocking
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */
class SecurityFilter implements FilterInterface
{
    /**
     * Patterns yang akan di-block (malicious scripts, encoded attacks)
     */
    private array $blockedPatterns = [
        // Script tags dan event handlers
        '/<script\b[^>]*>(.*?)<\/script>/is',
        '/on\w+\s*=\s*["\'][^"\']*["\']/i',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        
        // SQL Injection patterns
        '/(\bunion\b.*\bselect\b|\bselect\b.*\bfrom\b.*\bwhere\b)/i',
        '/(\binsert\b.*\binto\b|\bdelete\b.*\bfrom\b|\bdrop\b.*\btable\b)/i',
        '/(\bexec\b|\bexecute\b|\bxp_)/i',
        
        // Path traversal
        '/\.\.\/|\.\.\\\\/',
        
        // Null byte injection
        '/\x00/',
        
        // PHP tags
        '/<\?php/i',
        '/<\?=/i',
        '/<\?/i',
        '/\?>/i',
    ];

    /**
     * Encoded patterns (base64, hex, unicode obfuscation)
     */
    private array $encodedPatterns = [
        // Base64 encoded script tags
        '/PHNjcmlwdA/i',  // <script in base64
        '/PC9zY3JpcHQ/i', // </script in base64
        
        // Hex encoded
        '/0x[0-9a-f]+/i',
        
        // Unicode obfuscation
        '/\\\\u00[0-9a-f]{2}/i',
        '/&#x[0-9a-f]+;/i',
        '/&#[0-9]+;/i',
    ];

    /**
     * Before filter - sanitasi dan validasi semua input
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Get all input data
        $getData = $request->getGet() ?? [];
        $postData = $request->getPost() ?? [];
        
        // Check GET parameters
        foreach ($getData as $key => $value) {
            if ($this->isBlocked($value) || $this->isEncodedAttack($value)) {
                log_message('warning', "Blocked malicious GET request from IP: {$request->getIPAddress()}, Key: {$key}");
                return $this->blockRequest();
            }
        }
        
        // Check POST parameters
        foreach ($postData as $key => $value) {
            if ($this->isBlocked($value) || $this->isEncodedAttack($value)) {
                log_message('warning', "Blocked malicious POST request from IP: {$request->getIPAddress()}, Key: {$key}");
                return $this->blockRequest();
            }
        }
        
        // Check User-Agent for suspicious patterns
        $userAgent = $request->getUserAgent()->getAgentString();
        if ($this->isSuspiciousUserAgent($userAgent)) {
            log_message('warning', "Blocked suspicious User-Agent from IP: {$request->getIPAddress()}, UA: {$userAgent}");
            return $this->blockRequest();
        }
        
        // Check for oversized requests (DoS prevention)
        $contentLength = $request->getHeaderLine('Content-Length');
        if ($contentLength && (int)$contentLength > 10485760) { // 10MB max
            log_message('warning', "Blocked oversized request from IP: {$request->getIPAddress()}, Size: {$contentLength}");
            return $this->blockRequest();
        }
    }

    /**
     * After filter - add security headers
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add security headers
        return $response
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('X-Frame-Options', 'SAMEORIGIN')
            ->setHeader('X-XSS-Protection', '1; mode=block')
            ->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->setHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
    }

    /**
     * Check if value contains blocked patterns
     */
    private function isBlocked($value): bool
    {
        if (!is_string($value)) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    if ($this->isBlocked($v)) {
                        return true;
                    }
                }
            }
            return false;
        }
        
        foreach ($this->blockedPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check for encoded/obfuscated attack attempts
     */
    private function isEncodedAttack($value): bool
    {
        if (!is_string($value)) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    if ($this->isEncodedAttack($v)) {
                        return true;
                    }
                }
            }
            return false;
        }
        
        // Check encoded patterns
        foreach ($this->encodedPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                // Decode and check again
                $decoded = $this->decodeValue($value);
                if ($this->isBlocked($decoded)) {
                    return true;
                }
            }
        }
        
        // Try to detect base64 encoded malicious content
        if ($this->looksLikeBase64($value)) {
            $decoded = base64_decode($value, true);
            if ($decoded !== false && $this->isBlocked($decoded)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Decode various encoding schemes
     */
    private function decodeValue(string $value): string
    {
        // URL decode
        $decoded = urldecode($value);
        
        // HTML entity decode
        $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $decoded;
    }

    /**
     * Check if string looks like base64
     */
    private function looksLikeBase64(string $value): bool
    {
        // Must be at least 20 chars and match base64 pattern
        if (strlen($value) < 20) {
            return false;
        }
        
        return preg_match('/^[A-Za-z0-9+\/=]+$/', $value) === 1;
    }

    /**
     * Check for suspicious user agents (bots, scanners)
     */
    private function isSuspiciousUserAgent(string $userAgent): bool
    {
        $suspiciousAgents = [
            'sqlmap',
            'nikto',
            'nessus',
            'nmap',
            'masscan',
            'dirbuster',
            'gobuster',
            'wpscan',
            'acunetix',
            'burpsuite',
            'havij',
            'pangolin',
        ];
        
        $lowerUA = strtolower($userAgent);
        foreach ($suspiciousAgents as $agent) {
            if (strpos($lowerUA, $agent) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Block request and return error response
     */
    private function blockRequest()
    {
        // Return 403 Forbidden
        return service('response')
            ->setStatusCode(403)
            ->setBody('Access Denied: Suspicious activity detected.');
    }
}
