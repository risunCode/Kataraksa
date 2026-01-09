# 🔒 Laporan Security Hardening - Kataraksa

## 📋 Informasi Dokumen
| Item | Detail |
|------|--------|
| **Project** | Kataraksa - Sistem Perpustakaan Digital |
| **Versi** | 1.0.0 |
| **Tanggal** | 9 Januari 2026 |
| **Author** | Dimas |
| **Framework** | CodeIgniter 4 |

---

## 🎯 Executive Summary

Dokumen ini berisi laporan lengkap implementasi **Security Hardening** pada sistem Kataraksa. Proses hardening mencakup proteksi terhadap:

- ✅ **XSS (Cross-Site Scripting)** - Input sanitization & output escaping
- ✅ **SQL Injection** - Parameterized queries & input validation
- ✅ **CSRF (Cross-Site Request Forgery)** - Token validation
- ✅ **Script Injection** - Malicious script blocking
- ✅ **Brute Force Attack** - Rate limiting & account lockout
- ✅ **File Upload Attack** - Extension whitelist & execution prevention
- ✅ **Session Hijacking** - Session regeneration & IP binding
- ✅ **Encoded Attack** - Base64/Hex/Unicode obfuscation detection

---

## 🛡️ Security Layers Implemented

### Layer 1: Input Filtering (SecurityFilter)

```
Request → SecurityFilter → Controller
```

**File:** `app/Filters/SecurityFilter.php`

| Fitur | Deskripsi |
|-------|-----------|
| XSS Pattern Blocking | Deteksi `<script>`, event handlers (`onclick`, `onerror`), `javascript:` |
| SQL Injection Blocking | Deteksi `UNION SELECT`, `DROP TABLE`, `INSERT INTO` |
| Path Traversal Blocking | Deteksi `../`, `..\\` |
| PHP Tag Blocking | Deteksi `<?php`, `<?=`, `?>` |
| Null Byte Blocking | Deteksi `\x00` injection |
| Encoded Attack Detection | Deteksi Base64, Hex, Unicode obfuscation |
| Suspicious UA Blocking | Block scanner tools (sqlmap, nikto, burpsuite) |

**Blocked Patterns:**
```php
// Script tags & event handlers
'/<script\b[^>]*>(.*?)<\/script>/is'
'/on\w+\s*=\s*["\'][^"\']*["\']/i'
'/javascript\s*:/i'

// SQL Injection
'/(\bunion\b.*\bselect\b|\bselect\b.*\bfrom\b.*\bwhere\b)/i'
'/(\binsert\b.*\binto\b|\bdelete\b.*\bfrom\b|\bdrop\b.*\btable\b)/i'

// Encoded attacks (Base64)
'/PHNjcmlwdA/i'  // <script in base64
'/PC9zY3JpcHQ/i' // </script in base64
```

---

### Layer 2: Rate Limiting (RateLimitFilter)

```
Request → RateLimitFilter → Check Limits → Allow/Block
```

**File:** `app/Filters/RateLimitFilter.php`

| Parameter | Value | Deskripsi |
|-----------|-------|-----------|
| `maxRequests` | 60 | Max request per window |
| `windowSeconds` | 60 | Time window (1 menit) |
| `loginMaxAttempts` | 5 | Max login attempts |
| `loginLockoutTime` | 900 | Lockout duration (15 menit) |

**Response Headers:**
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 55
X-RateLimit-Reset: 1736380800
```

**Lockout Behavior:**
1. User gagal login 5x → Account locked 15 menit
2. IP tercatat di cache dengan TTL 15 menit
3. Setelah lockout, counter di-reset

---

### Layer 3: Security Headers

**Added via SecurityFilter::after()**

| Header | Value | Fungsi |
|--------|-------|--------|
| `X-Content-Type-Options` | `nosniff` | Prevent MIME sniffing |
| `X-Frame-Options` | `SAMEORIGIN` | Prevent clickjacking |
| `X-XSS-Protection` | `1; mode=block` | Browser XSS filter |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Control referrer info |
| `Permissions-Policy` | `geolocation=(), microphone=(), camera=()` | Disable sensitive APIs |

---

### Layer 4: Authentication Security

**File:** `app/Controllers/Auth.php`

| Fitur | Implementasi |
|-------|--------------|
| Session Regeneration | `session_regenerate_id(true)` setelah login |
| IP Binding | Session menyimpan `ip_address` user |
| Login Time Tracking | Session menyimpan `login_time` |
| Failed Login Logging | Log ke file dengan IP & timestamp |
| Generic Error Message | "Email atau password salah" (tidak reveal mana yang salah) |
| Safe Redirect | Validasi URL redirect sebelum redirect |

**Session Data Structure:**
```php
$sessionData = [
    'isLoggedIn'   => true,
    'user_id'      => $user['id'],
    'user_name'    => $user['name'],
    'user_email'   => $user['email'],
    'role'         => $user['role'],
    'login_time'   => time(),
    'ip_address'   => $this->request->getIPAddress(),
];
```

---

### Layer 5: File Upload Security

**Location:** `public/uploads/` & `public/uploads/covers/`

| Protection | Method |
|------------|--------|
| PHP Execution Disabled | `.htaccess` dengan `php_flag engine off` |
| Extension Whitelist | Hanya `.jpg`, `.jpeg`, `.png`, `.webp` |
| MIME Type Validation | CI4 validation rules |
| Random Filename | `$cover->getRandomName()` |
| Directory Listing Disabled | `Options -Indexes` |
| Script Handler Disabled | `Options -ExecCGI` |

**.htaccess Rules:**
```apache
# Disable PHP execution
<FilesMatch "\.php$">
    Order Deny,Allow
    Deny from all
</FilesMatch>

# Only allow images
<FilesMatch "(?i)\.(gif|jpe?g|png|webp)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>
```

---

### Layer 6: Security Helper Functions

**File:** `app/Helpers/security_helper.php`

| Function | Deskripsi |
|----------|-----------|
| `sanitize_input()` | Sanitasi string, remove XSS |
| `sanitize_filename()` | Sanitasi filename, remove path traversal |
| `validate_email_strict()` | Validasi email dengan strict checking |
| `validate_password_strength()` | Validasi kekuatan password |
| `is_safe_url()` | Validasi URL untuk redirect |
| `mask_email()` | Mask email untuk privacy |
| `log_security_event()` | Log security events |

**Password Strength Requirements:**
- Minimal 8 karakter
- Maksimal 128 karakter
- Harus ada huruf besar (A-Z)
- Harus ada huruf kecil (a-z)
- Harus ada angka (0-9)
- Harus ada karakter spesial (!@#$%^&*)
- Tidak boleh password umum (password123, admin123, dll)

---

## 📊 Security Matrix

| Attack Vector | Protection | Status |
|---------------|------------|--------|
| XSS (Reflected) | SecurityFilter + esc() | ✅ Protected |
| XSS (Stored) | Input sanitization + output escaping | ✅ Protected |
| SQL Injection | CI4 Query Builder + pattern blocking | ✅ Protected |
| CSRF | Token validation (enable in production) | ⚠️ Ready |
| Brute Force | RateLimitFilter (5 attempts/15 min lockout) | ✅ Protected |
| Session Hijacking | Session regeneration + IP binding | ✅ Protected |
| File Upload RCE | Extension whitelist + .htaccess | ✅ Protected |
| Path Traversal | Pattern blocking + filename sanitization | ✅ Protected |
| Clickjacking | X-Frame-Options header | ✅ Protected |
| MIME Sniffing | X-Content-Type-Options header | ✅ Protected |
| Encoded Attacks | Base64/Hex/Unicode detection | ✅ Protected |
| Scanner Tools | User-Agent blocking | ✅ Protected |

---

## 🔧 Configuration Files Modified

### 1. app/Config/Filters.php
```php
// New filters registered
'security'  => SecurityFilter::class,
'ratelimit' => RateLimitFilter::class,

// Global filters enabled
public array $globals = [
    'before' => [
        'security',     // XSS, SQL Injection, Script blocking
        'ratelimit',    // Rate limiting
        'invalidchars', // Invalid character filtering
    ],
    'after' => [
        'security',     // Security headers
    ],
];
```

### 2. app/Controllers/Auth.php
- Added rate limit integration
- Added session regeneration
- Added security event logging
- Added safe URL validation

### 3. public/uploads/.htaccess
- Disabled PHP execution
- Enabled image-only access
- Disabled directory listing

---

## 📝 Security Event Logging

**Log Format:**
```json
{
    "event": "LOGIN_FAILED",
    "ip": "192.168.1.100",
    "user_agent": "Mozilla/5.0...",
    "timestamp": "2026-01-09 10:30:45",
    "data": {
        "email": "test@example.com",
        "reason": "wrong_password"
    }
}
```

**Events Logged:**
| Event | Trigger |
|-------|---------|
| `LOGIN_SUCCESS` | User berhasil login |
| `LOGIN_FAILED` | User gagal login |
| `BLOCKED_REQUEST` | Request di-block oleh SecurityFilter |
| `RATE_LIMIT_EXCEEDED` | User melebihi rate limit |

**Log Location:** `writable/logs/log-YYYY-MM-DD.log`

---

## 🚀 Production Checklist

Sebelum deploy ke production, pastikan:

- [ ] Enable CSRF protection di `Filters.php`
- [ ] Enable Honeypot filter
- [ ] Set `CI_ENVIRONMENT = production` di `.env`
- [ ] Set `app.forceGlobalSecureRequests = true` (HTTPS)
- [ ] Review dan sesuaikan rate limit values
- [ ] Setup log rotation untuk security logs
- [ ] Backup database secara berkala
- [ ] Monitor security logs secara rutin

---

## 📚 Files Created/Modified

### New Files Created:
```
app/Filters/SecurityFilter.php      # XSS, SQL Injection, Script blocking
app/Filters/RateLimitFilter.php     # Rate limiting & brute force prevention
app/Helpers/security_helper.php     # Security helper functions
public/uploads/.htaccess            # Upload folder protection
public/uploads/covers/.htaccess     # Covers folder protection
SECURITY_HARDENING.md               # This document
```

### Files Modified:
```
app/Config/Filters.php              # Register new filters
app/Controllers/Auth.php            # Add security features
```

---

## 🔍 Testing Recommendations

### 1. XSS Testing
```html
<!-- Try injecting in book title -->
<script>alert('XSS')</script>
<img src=x onerror="alert('XSS')">
```
**Expected:** Request blocked with 403

### 2. SQL Injection Testing
```
' OR '1'='1
'; DROP TABLE books; --
UNION SELECT * FROM users
```
**Expected:** Request blocked with 403

### 3. Brute Force Testing
```bash
# Try 6 failed logins
for i in {1..6}; do
  curl -X POST /login -d "email=test@test.com&password=wrong"
done
```
**Expected:** 429 Too Many Requests after 5 attempts

### 4. File Upload Testing
```bash
# Try uploading PHP file
curl -X POST /admin/books/store -F "cover=@shell.php"
```
**Expected:** Validation error, file rejected

---

## 📞 Contact & Support

| Item | Detail |
|------|--------|
| **Author** | Dimas |
| **Project** | Kataraksa |
| **Institution** | Universitas Bina Sarana Informatika |
| **Purpose** | Tugas Sertifikat Kompetensi (Serkom) |

---

*Document generated: 9 Januari 2026*
*Security Hardening Version: 1.0.0*
