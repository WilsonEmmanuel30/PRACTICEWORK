# PHPMailer Authentication System - Setup Guide

This guide will help you set up the complete authentication system with PHPMailer for email verification and password reset functionality.

## Prerequisites

- PHP 7.4 or higher
- MySQL database
- XAMPP, WAMP, or similar local server
- Gmail account with App Password enabled

## Step 1: Create the Database Table

1. Open **phpMyAdmin** in your browser (usually at `http://localhost/phpmyadmin`)
2. Select your `practicework` database
3. Go to the **SQL** tab
4. Copy and paste the SQL from `database_setup.sql` file:

```sql
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

5. Click **Execute** button

## Step 2: Configure Gmail SMTP

### Enable 2-Factor Authentication
1. Go to https://myaccount.google.com/
2. Click **Security** in the left menu
3. Enable **2-Step Verification**

### Generate App Password
1. Go back to Security settings
2. Look for **App passwords** (appears only if 2-Step is enabled)
3. Select **Mail** and **Windows Computer** (or your device)
4. Generate and copy the 16-character password

## Step 3: Update Gmail Credentials in code.php

Open `/code.php` and update these lines with your Gmail credentials:

```php
define('GMAIL_USERNAME', 'yourgmail@gmail.com');  // Your Gmail address
define('GMAIL_PASSWORD', 'your_app_password');     // 16-char app password
define('SITE_URL', 'http://localhost/PRACTICEWORK'); // Your local URL
```

Replace:
- `yourgmail@gmail.com` with your actual Gmail address
- `your_app_password` with the 16-character app password you generated

## Step 4: File Structure

The authentication system includes the following files:

```
PRACTICEWORK/
├── code.php                 # Handles registration, login, forgot/reset password
├── register.php             # Registration form
├── login.php                # Login form with forgot password link
├── verify-email.php         # Email verification handler
├── forgot-password.php      # Forgot password request form
├── reset-password.php       # Password reset form
├── logout.php               # Logout handler
├── dashboard.php            # Protected user dashboard
├── database_setup.sql       # Database schema
├── dbcon.php               # Database connection
├── SETUP_GUIDE.md          # This file
└── includes/
    ├── header.php
    ├── navbar.php
    └── footer.php
```

## Step 5: Features Implemented

### 1. Registration
- Validates all required fields
- Checks email format
- Confirms password matches
- Validates password strength (minimum 6 characters)
- Prevents duplicate email registration
- Hashes password using PHP's password_hash()
- Generates unique verification token
- Sends verification email automatically

### 2. Email Verification
- User receives verification email with unique token link
- Clicking the link marks the email as verified
- User cannot login until email is verified
- Token is cleared after verification

### 3. Login
- Validates email and password
- Checks if email is verified
- Uses password_verify() for secure comparison
- Creates session for logged-in user
- Prevents access to protected pages without login

### 4. Forgot Password
- User enters their email address
- System checks if email exists (without revealing whether it does)
- Generates unique reset token
- Sends password reset email
- Token expires after 1 hour

### 5. Reset Password
- Validates token and expiration
- Requires new password confirmation
- Validates password strength
- Updates password securely
- Clears reset token after successful reset
- Redirects to login page

### 6. Dashboard
- Protected page - requires login
- Displays user information
- Includes logout button

## Step 6: Testing the System

### Test Registration:
1. Go to `http://localhost/PRACTICEWORK/register.php`
2. Fill in the form with:
   - Name: Test User
   - Phone: 1234567890
   - Email: your-email@gmail.com
   - Password: Test123 (minimum 6 characters)
   - Confirm Password: Test123
3. Click "REGISTER NOW"
4. Check your email for verification link

### Test Email Verification:
1. Click the verification link in the email
2. Should see success message
3. Click "Proceed to Login"

### Test Login:
1. Go to `http://localhost/PRACTICEWORK/login.php`
2. Enter your email and password
3. Should redirect to dashboard

### Test Forgot Password:
1. Go to `http://localhost/PRACTICEWORK/forgot-password.php`
2. Enter your email
3. Check your email for reset link
4. Click the link and enter new password
5. Login with new password

### Test Logout:
1. Click "Logout" button on dashboard
2. Should redirect to login page

## Step 7: Security Best Practices

The system includes:

✅ **Password Hashing**: Uses PHP's `password_hash()` function
✅ **SQL Injection Prevention**: Uses prepared statements with mysqli_prepare()
✅ **Email Verification**: Prevents unauthorized account creation
✅ **Token Expiration**: Reset tokens expire after 1 hour
✅ **Secure Cookies**: Session data stored in PHP $_SESSION
✅ **Input Validation**: Email format and password requirements checked
✅ **HTTPS Ready**: System works with HTTPS (replace http:// in SITE_URL when deployed)

## Step 8: Troubleshooting

### "sent or not" message appears
- Gmail credentials in code.php are incorrect
- 2-Factor authentication not enabled
- App password not created
- Check Gmail's security settings for app access

### Email not received
1. Check spam/junk folder
2. Verify Gmail credentials
3. Check if 2-Factor authentication is enabled
4. Ensure App password is used, not regular password

### "Email already exists" error
- The email is already registered
- Try with a different email address

### Can't login after email verification
- Make sure email is verified (check is_verified = 1 in database)
- Password is case-sensitive
- Check that password_hash() is working correctly

### Reset link doesn't work
- Token may have expired (1 hour timeout)
- Request a new password reset link
- Check that reset_token_expire is in the future

## Step 9: Customization

### Change Email Template
Edit the `sendemail_verify()` function in `code.php` to customize the verification email:

```php
$mail->Body = "
    <html>
    <head><style>body{font-family: Arial, sans-serif;}</style></head>
    <body>
        <h2>Hello $name,</h2>
        <p>Your custom message here</p>
        <p><a href='$verification_link'>Verify Email</a></p>
    </body>
    </html>
";
```

### Change Password Requirements
Modify the password validation in `code.php` (currently minimum 6 characters):

```php
if (strlen($password) < 8) {  // Change 6 to 8 for 8 characters
    $_SESSION['status'] = "Password must be at least 8 characters long!";
}
```

### Change Token Expiration Time
Modify the reset token expiration in `code.php`:

```php
$reset_token_expire = date('Y-m-d H:i:s', strtotime('+2 hours')); // Change +1 hour to desired time
```

## Step 10: Production Deployment

When deploying to a live server:

1. Update `SITE_URL` in code.php to your domain
2. Update Gmail credentials if using different email
3. Use HTTPS (update http:// to https://)
4. Move database credentials to environment variables
5. Set proper file permissions
6. Enable HTTPS for SMTP connection in code.php

## Support

If you encounter any issues:
1. Check the error messages displayed in the browser
2. Check PHP error logs in your server
3. Verify all database fields exist with correct names
4. Ensure Gmail credentials are correct

---

**Last Updated**: March 2025
**System Version**: 1.0
