<?php

/**
 * security_helper.php
 * Helper functions untuk sanitasi dan validasi input
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */

if (!function_exists('sanitize_input')) {
    /**
     * Sanitasi input string - remove XSS, SQL injection attempts
     * 
     * @param string|null $input
     * @param bool $allowHtml Allow safe HTML tags
     * @return string
     */
    function sanitize_input(?string $input, bool $allowHtml = false): string
    {
        if ($input === null || $input === '') {
            return '';
        }
        
        // Trim whitespace
        $input = trim($input);
        
        // Remove null bytes
        $input = str_replace("\0", '', $input);
        
        // Remove invisible characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $input);
        
        if ($allowHtml) {
            // Allow only safe HTML tags
            $input = strip_tags($input, '<p><br><b><i><u><strong><em><ul><ol><li>');
        } else {
            // Strip all HTML tags
            $input = strip_tags($input);
        }
        
        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $input;
    }
}

if (!function_exists('sanitize_filename')) {
    /**
     * Sanitasi filename - remove path traversal, special chars
     * 
     * @param string $filename
     * @return string
     */
    function sanitize_filename(string $filename): string
    {
        // Remove path components
        $filename = basename($filename);
        
        // Remove null bytes
        $filename = str_replace("\0", '', $filename);
        
        // Remove special characters except alphanumeric, dash, underscore, dot
        $filename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', $filename);
        
        // Remove multiple dots (prevent .php.jpg attacks)
        $filename = preg_replace('/\.+/', '.', $filename);
        
        // Limit length
        if (strlen($filename) > 255) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $filename = substr($name, 0, 250 - strlen($ext)) . '.' . $ext;
        }
        
        return $filename;
    }
}

if (!function_exists('validate_email_strict')) {
    /**
     * Validasi email dengan strict checking
     * 
     * @param string $email
     * @return bool
     */
    function validate_email_strict(string $email): bool
    {
        // Basic filter validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Check for suspicious patterns
        $suspicious = [
            '/\+.*@/',      // Plus addressing (can be used for enumeration)
            '/\.\./',       // Double dots
            '/@.*@/',       // Multiple @ signs
            '/[<>"\']/',    // HTML/SQL chars
        ];
        
        foreach ($suspicious as $pattern) {
            if (preg_match($pattern, $email)) {
                return false;
            }
        }
        
        // Check domain has MX record (optional, can be slow)
        // $domain = substr(strrchr($email, "@"), 1);
        // if (!checkdnsrr($domain, "MX")) {
        //     return false;
        // }
        
        return true;
    }
}

if (!function_exists('validate_password_strength')) {
    /**
     * Validasi kekuatan password
     * 
     * @param string $password
     * @return array ['valid' => bool, 'errors' => array]
     */
    function validate_password_strength(string $password): array
    {
        $errors = [];
        
        // Minimum length
        if (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter.';
        }
        
        // Maximum length (prevent DoS)
        if (strlen($password) > 128) {
            $errors[] = 'Password maksimal 128 karakter.';
        }
        
        // Must contain uppercase
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf besar.';
        }
        
        // Must contain lowercase
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf kecil.';
        }
        
        // Must contain number
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password harus mengandung angka.';
        }
        
        // Must contain special character
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $errors[] = 'Password harus mengandung karakter spesial.';
        }
        
        // Check for common passwords
        $commonPasswords = [
            'password', '123456', '12345678', 'qwerty', 'abc123',
            'password123', 'admin123', 'letmein', 'welcome',
        ];
        
        if (in_array(strtolower($password), $commonPasswords)) {
            $errors[] = 'Password terlalu umum, gunakan password yang lebih unik.';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
}

if (!function_exists('is_safe_url')) {
    /**
     * Check if URL is safe for redirect
     * 
     * @param string $url
     * @return bool
     */
    function is_safe_url(string $url): bool
    {
        // Must be relative URL or same domain
        $parsed = parse_url($url);
        
        // Relative URL is safe
        if (!isset($parsed['host'])) {
            // Check for javascript: or data: schemes
            if (preg_match('/^(javascript|data|vbscript):/i', $url)) {
                return false;
            }
            return true;
        }
        
        // Check if same domain
        $currentHost = $_SERVER['HTTP_HOST'] ?? '';
        if ($parsed['host'] === $currentHost) {
            return true;
        }
        
        return false;
    }
}

if (!function_exists('generate_csrf_token')) {
    /**
     * Generate CSRF token
     * 
     * @return string
     */
    function generate_csrf_token(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verify_csrf_token')) {
    /**
     * Verify CSRF token
     * 
     * @param string $token
     * @return bool
     */
    function verify_csrf_token(string $token): bool
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('mask_email')) {
    /**
     * Mask email for display (privacy)
     * 
     * @param string $email
     * @return string
     */
    function mask_email(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return '***@***.***';
        }
        
        $name = $parts[0];
        $domain = $parts[1];
        
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2));
        $domainParts = explode('.', $domain);
        $maskedDomain = substr($domainParts[0], 0, 1) . '***.' . end($domainParts);
        
        return $maskedName . '@' . $maskedDomain;
    }
}

if (!function_exists('log_security_event')) {
    /**
     * Log security event
     * 
     * @param string $event
     * @param array $data
     * @return void
     */
    function log_security_event(string $event, array $data = []): void
    {
        $logData = [
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'timestamp' => date('Y-m-d H:i:s'),
            'data' => $data,
        ];
        
        log_message('warning', '[SECURITY] ' . json_encode($logData));
    }
}
