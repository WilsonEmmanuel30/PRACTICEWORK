# Quick Start Guide - PHPMailer Authentication System

## 3-Minute Setup

### 1. Database Setup (1 minute)
```
1. Open phpMyAdmin → http://localhost/phpmyadmin
2. Select your 'practicework' database
3. Go to SQL tab
4. Copy content from database_setup.sql
5. Click Execute
```

### 2. Gmail Configuration (1 minute)
```
1. Go to myaccount.google.com → Security
2. Enable 2-Step Verification
3. Go to App passwords
4. Generate and copy 16-character password
```

### 3. Update Credentials (1 minute)
Open `code.php` and update:
```php
define('GMAIL_USERNAME', 'your-email@gmail.com');  // Your Gmail
define('GMAIL_PASSWORD', 'your_16_char_password');  // App password
define('SITE_URL', 'http://localhost/PRACTICEWORK');
```

## File Overview

| File | Purpose |
|------|---------|
| `code.php` | All backend logic (registration, login, email, password reset) |
| `register.php` | Registration form page |
| `login.php` | Login form page with forgot password link |
| `verify-email.php` | Email verification handler |
| `forgot-password.php` | Request password reset form |
| `reset-password.php` | Password reset form |
| `dashboard.php` | Protected user dashboard |
| `logout.php` | Logout handler |

## Features Implemented

✅ **Registration**
- Email validation
- Password confirmation
- Duplicate email prevention
- Automatic verification email

✅ **Email Verification**
- Unique verification tokens
- Token-based verification
- Blocks login until verified

✅ **Login**
- Email/password authentication
- Email verification check
- Session management
- Secure password comparison

✅ **Forgot Password**
- Email-based recovery
- Unique reset tokens
- 1-hour token expiration

✅ **Reset Password**
- Secure password update
- Token validation
- Automatic redirect to login

## User Flow

```
Register → Check Email → Verify Email → Login → Dashboard → Logout
                                   ↓
                          (if forgot password)
                                   ↓
                        Request Password Reset → Check Email → 
                                   ↓
                          Click Reset Link → New Password → Login
```

## Testing Checklist

- [ ] Register with new email
- [ ] Receive verification email
- [ ] Click verification link
- [ ] Login with verified email
- [ ] View dashboard with user info
- [ ] Logout successfully
- [ ] Click forgot password link
- [ ] Receive reset email
- [ ] Click reset link and change password
- [ ] Login with new password

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Email not received | Check Gmail credentials and 2-Factor auth |
| "sent or not" message | Gmail credentials incorrect in code.php |
| Can't login | Verify email first, then try again |
| Password reset link expired | Link valid for 1 hour, request new one |
| "Email already exists" | Use different email or login existing account |

## Database Schema Summary

**users table** has these fields:
- `id` - Unique user ID
- `name` - User's full name
- `phone` - User's phone number
- `email` - User's email (unique)
- `password` - Hashed password
- `verify_token` - Email verification token
- `is_verified` - Email verification status (0=not verified, 1=verified)
- `reset_token` - Password reset token
- `reset_token_expire` - Reset token expiration time
- `created_at` - Account creation timestamp
- `updated_at` - Last update timestamp

## Key Security Features

🔒 **Password Hashing** - Uses PHP password_hash()
🔒 **SQL Injection Protection** - Prepared statements
🔒 **Email Verification** - Prevents unauthorized accounts
🔒 **Token Expiration** - Reset tokens expire after 1 hour
🔒 **Session Management** - Secure session handling
🔒 **Input Validation** - Email format and password requirements

## Next Steps

1. Follow the 3-minute setup above
2. Test all features from the Testing Checklist
3. Customize email templates if needed (see SETUP_GUIDE.md)
4. Deploy to production with HTTPS

For detailed information, see **SETUP_GUIDE.md**

---
**Version**: 1.0
**Last Updated**: March 2025
