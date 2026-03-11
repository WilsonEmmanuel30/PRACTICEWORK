# PHPMailer Authentication System - Quick Reference Card

## URLs & Navigation

```
Registration    → http://localhost/PRACTICEWORK/register.php
Login          → http://localhost/PRACTICEWORK/login.php
Dashboard      → http://localhost/PRACTICEWORK/dashboard.php (protected)
Forgot Password → http://localhost/PRACTICEWORK/forgot-password.php
Reset Password  → http://localhost/PRACTICEWORK/reset-password.php?token=XXX
Verify Email    → http://localhost/PRACTICEWORK/verify-email.php?token=XXX
Logout         → http://localhost/PRACTICEWORK/logout.php
```

## Configuration (code.php)

```php
// Line 11-13: Update these with your Gmail credentials
define('GMAIL_USERNAME', 'yourgmail@gmail.com');
define('GMAIL_PASSWORD', 'your_16_char_app_password');
define('SITE_URL', 'http://localhost/PRACTICEWORK');
```

## Database Setup

Run in phpMyAdmin:
```sql
-- Copy from database_setup.sql and execute
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    verify_token VARCHAR(255),
    is_verified INT DEFAULT 0,
    reset_token VARCHAR(255),
    reset_token_expire DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_verify_token (verify_token),
    INDEX idx_reset_token (reset_token)
);
```

## Test Credentials Format

**Registration:**
- Name: John Doe
- Phone: 1234567890
- Email: john@example.com
- Password: Password123 (min 6 chars)
- Confirm: Password123

**Login:**
- Email: john@example.com
- Password: Password123

## Session Variables

After successful login:
```php
$_SESSION['user_id']    // User's database ID
$_SESSION['user_name']  // User's full name
$_SESSION['user_email'] // User's email address
```

## Key Functions in code.php

```php
// Send verification email
sendemail_verify($name, $email, $verify_token);

// Send password reset email
sendemail_reset($name, $email, $reset_token);
```

## Database Query Examples

**Check if email exists:**
```sql
SELECT email FROM users WHERE email = 'user@example.com' LIMIT 1;
```

**Check if verified:**
```sql
SELECT is_verified FROM users WHERE email = 'user@example.com';
```

**Check reset token:**
```sql
SELECT * FROM users WHERE reset_token = 'token123' AND reset_token_expire > NOW();
```

## Error Messages Users Will See

| Error | Cause |
|-------|-------|
| All fields are required! | Empty field in form |
| Invalid email format! | Email doesn't have @ symbol |
| Passwords do not match! | Password fields different |
| Password must be at least 6 characters long! | Password too short |
| Email already registered! | Email already in database |
| Please verify your email first! | Email not verified yet (is_verified = 0) |
| Invalid email or password! | Wrong email or password |
| Invalid or expired reset link | Token invalid or expired |
| Password reset successfully! | Password changed, can login |

## File Modification Checklist

Before going live, verify these files are updated:

| File | What to Update | Status |
|------|----------------|--------|
| code.php | GMAIL_USERNAME, GMAIL_PASSWORD, SITE_URL | ✓ |
| database_setup.sql | Run in phpMyAdmin | ✓ |
| register.php | No changes needed | ✓ |
| login.php | No changes needed | ✓ |
| verify-email.php | No changes needed | ✓ |
| forgot-password.php | No changes needed | ✓ |
| reset-password.php | No changes needed | ✓ |
| dashboard.php | No changes needed | ✓ |
| logout.php | No changes needed | ✓ |

## Email Settings Reference

**Gmail SMTP Configuration:**
```
Host: smtp.gmail.com
Port: 587
Encryption: STARTTLS
Username: Your Gmail address
Password: 16-character App Password (NOT regular password)
```

**Requirements:**
- Gmail account with 2-Factor Authentication
- App-specific password generated
- Less secure app access NOT needed

## Common Tasks

### Generate Test Token
```php
$token = md5(rand() . time());
echo $token;  // Copy for testing
```

### Hash a Password
```php
$hashed = password_hash('password123', PASSWORD_DEFAULT);
```

### Verify a Password
```php
if (password_verify('password123', $hashed)) {
    echo "Password is correct";
}
```

### Check Database Connection
```php
if ($con) {
    echo "Connected successfully";
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
```

## Debug Tips

### Check if Session is Created
```php
<?php
session_start();
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
?>
```

### View PHP Errors
Add to top of file:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Check Database
```sql
-- View all users
SELECT * FROM users;

-- Check specific user
SELECT * FROM users WHERE email = 'user@example.com';

-- Check tokens
SELECT email, verify_token, is_verified FROM users WHERE email = 'user@example.com';

-- Check reset tokens
SELECT email, reset_token, reset_token_expire FROM users WHERE reset_token IS NOT NULL;
```

### Email Troubleshooting
Check Gmail account:
1. Go to myaccount.google.com/security
2. Check "Your devices" → "Manage all devices"
3. Look for recent app access
4. Verify App Password is correct

## Performance Metrics (Expected)

| Operation | Time |
|-----------|------|
| Registration | < 2 seconds |
| Email sending | < 5 seconds |
| Login | < 1 second |
| Email verification | < 1 second |
| Password reset | < 2 seconds |
| Dashboard load | < 1 second |

## Security Checklist

Before deployment:
- [ ] GMAIL_USERNAME updated
- [ ] GMAIL_PASSWORD updated (App password, not regular)
- [ ] SITE_URL updated for production
- [ ] Database table created
- [ ] All PHP files in place
- [ ] PHPMailer included properly
- [ ] Error logging configured
- [ ] HTTPS enabled (for production)
- [ ] File permissions set correctly
- [ ] Database backed up

## File Sizes Reference

| File | Size | Lines |
|------|------|-------|
| code.php | ~13 KB | 340+ |
| register.php | ~2 KB | 60+ |
| login.php | ~4 KB | 130+ |
| verify-email.php | ~2 KB | 79 |
| forgot-password.php | ~2 KB | 55 |
| reset-password.php | ~3 KB | 92 |
| dashboard.php | ~2 KB | 48 |
| logout.php | ~1 KB | 14 |

## Documentation Files

| Document | Purpose | Read Time |
|----------|---------|-----------|
| README.md | Overview | 5 min |
| QUICK_START.md | Setup | 3 min |
| SETUP_GUIDE.md | Detailed setup | 15 min |
| IMPLEMENTATION_SUMMARY.md | Features | 20 min |
| SYSTEM_ARCHITECTURE.md | Technical | 25 min |
| VERIFICATION_CHECKLIST.md | Testing | 30 min |

## Helpful Links

**Gmail Account Setup:**
- https://myaccount.google.com
- https://myaccount.google.com/security

**PHP Documentation:**
- https://www.php.net/manual/en/function.password-hash.php
- https://www.php.net/manual/en/function.password-verify.php
- https://www.php.net/manual/en/function.mysqli-prepare.php

**PHPMailer:**
- Documentation in phpmailer-main/ folder
- SMTP reference

## Regex Patterns Used

Email validation (PHP filter):
```php
filter_var($email, FILTER_VALIDATE_EMAIL)
```

Password hashing:
```php
password_hash($password, PASSWORD_DEFAULT)
password_verify($password, $hash)
```

Token generation:
```php
md5(rand() . time())
```

## Response Status Codes (Implied)

| Redirect | Meaning |
|----------|---------|
| → register.php | After registration attempt |
| → login.php | After verification/password reset |
| → dashboard.php | After successful login |
| → forgot-password.php | After reset attempt |
| → verify-email.php?token | From email link |
| → reset-password.php?token | From email link |

## Session Lifetime

- Default PHP session timeout: 24 minutes of inactivity
- Configurable in php.ini: `session.gc_maxlifetime`
- Can be custom set in code with: `session_set_cookie_params(3600)`

## Testing Quick Commands

Reset database:
```sql
DROP TABLE users;
-- Then run CREATE TABLE from database_setup.sql
```

Clear all sessions:
```bash
# On Linux/Mac
rm -rf /tmp/sess_*

# On Windows
# Delete temp files in Windows temp folder
```

## Troubleshooting Flowchart

```
User reports issue
    ↓
Check error message displayed
    ↓
Look up error in "Error Messages" section
    ↓
Check that cause
    ↓
Found cause?
    ├─ YES → Fix
    └─ NO → Check documentation
```

---

**Tip**: Keep this file open while troubleshooting!

**Last Updated**: March 2025
**Version**: 1.0
