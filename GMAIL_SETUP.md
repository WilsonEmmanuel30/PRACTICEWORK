# Gmail Configuration for PHPMailer - Complete Setup Guide

## Problem: Emails Not Sending?

If you're not receiving verification or password reset emails, it's usually a Gmail configuration issue. Follow this guide step-by-step.

---

## Step 1: Enable 2-Factor Authentication (Required)

Gmail requires 2-Factor Authentication to generate App Passwords. Follow these steps:

1. Go to **https://myaccount.google.com/security**
2. Look for **"How you sign in to Google"** section
3. Click on **"2-Step Verification"**
4. Click **"Get Started"**
5. Follow the prompts to verify your phone number
6. Complete the 2-Step Verification setup

Once complete, you should see a checkmark next to "2-Step Verification".

---

## Step 2: Generate Gmail App Password

This is the **most important step** to get emails working.

1. After 2-Factor Auth is enabled, go to **https://myaccount.google.com/apppasswords**
2. You should see a dropdown for **"Select the app"** and **"Select the device"**
3. Select:
   - **App**: "Mail"
   - **Device**: "Windows Computer" (or your device type)
4. Click **"Generate"**
5. Google will show a **16-character password** (usually in yellow box)
6. **Copy this entire password** (including spaces if any)

Example: `abcd efgh ijkl mnop`

---

## Step 3: Update code.php with Gmail Credentials

1. Open **code.php** in your text editor
2. Find lines 13-14 (around the top):

```php
define('GMAIL_USERNAME', 'yourgmail@gmail.com');
define('GMAIL_PASSWORD', 'your_app_password');
```

3. Replace with your actual details:

```php
define('GMAIL_USERNAME', 'your.email@gmail.com');  // Your full Gmail address
define('GMAIL_PASSWORD', 'abcd efgh ijkl mnop');   // 16-char App Password
```

**IMPORTANT**: 
- Use the **16-character App Password**, NOT your regular Gmail password
- Include spaces in the App Password if they appear
- Make sure your Gmail address is correct

4. **Save the file**

---

## Step 4: Test Email Sending

1. Go to **http://localhost/practicework1/register.php**
2. Fill in the registration form:
   - Name: Your name
   - Phone: Any phone number
   - Email: **Use YOUR email address** (the one you're checking)
   - Password: password123
   - Confirm Password: password123

3. Click **"REGISTER NOW"**

4. Check your email inbox (and spam folder) for the verification email

---

## Troubleshooting Email Issues

### Issue 1: "Authentication Failed"
**Cause**: Credentials are wrong
- Check your Gmail address is correct
- Verify you're using the 16-character App Password
- Make sure there are no extra spaces before/after

### Issue 2: "Could not connect to SMTP server"
**Cause**: Network or firewall issue
- Check your internet connection
- Gmail SMTP requires Port 587
- Some firewalls block SMTP - check with your IT admin

### Issue 3: Email arrives but link doesn't work
**Cause**: SITE_URL is wrong
- Edit code.php line 15
- Change to your actual URL (currently `http://localhost/practicework1`)

### Issue 4: "Error: SMTP login failed"
**Solutions**:
1. Ensure 2-Factor Authentication is ENABLED
2. Ensure you have an App Password (not regular password)
3. Try generating a NEW App Password
4. Wait 5 minutes after creating App Password before using it

### Issue 5: Email doesn't appear anywhere
**Solutions**:
1. Check your SPAM/JUNK folder
2. Check the email address you entered (typos?)
3. Check if Gmail account is secure (no unusual activity)
4. Try with a different email address (test with Gmail account)

---

## Enable Email Debugging (Advanced)

If emails still don't work, enable debugging:

1. Open **code.php**
2. Find this line in **sendemail_verify()** function (around line 23):
   ```php
   $mail->SMTPDebug = 0;
   ```
3. Change to:
   ```php
   $mail->SMTPDebug = 2;
   ```
4. Also find it in **sendemail_reset()** function (around line 75) and change it there too

5. Now when you try to register, you'll see detailed error messages

6. **After debugging, change back to 0** to hide debug messages

---

## Verify Gmail App Password Works

Before testing your app, verify the credentials work:

1. Create a simple test file **test_email.php** with:

```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer-main/PHPMailer/Exception.php';
require 'phpmailer-main/PHPMailer/PHPMailer.php';
require 'phpmailer-main/PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your.email@gmail.com';        // Your Gmail
    $mail->Password = 'abcd efgh ijkl mnop';         // 16-char App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('your.email@gmail.com');
    $mail->addAddress('your.email@gmail.com');       // Send to yourself
    $mail->Subject = 'Test Email';
    $mail->Body = 'If you see this, Gmail is working!';

    if($mail->send()) {
        echo 'TEST EMAIL SENT SUCCESSFULLY!';
    }
} catch (Exception $e) {
    echo 'TEST EMAIL FAILED: ' . $mail->ErrorInfo;
}
?>
```

2. Visit **http://localhost/practicework1/test_email.php**
3. You should see either:
   - "TEST EMAIL SENT SUCCESSFULLY!" → Gmail is working
   - "TEST EMAIL FAILED: ..." → Gmail needs fixing

4. Delete **test_email.php** after testing

---

## Step-by-Step Testing Flow

Once Gmail is working:

### Complete Test 1: Registration + Verification

```
1. Go to /register.php
2. Register with YOUR email address
3. See "Registration successful!" message
4. Check email inbox for "Email Verification - Instrumentalist Hub"
5. Click "Verify Email Address" link
6. See "Email verification successful!"
7. Go to /login.php
8. Login with your credentials
9. You should see the dashboard!
```

### Complete Test 2: Forgot Password

```
1. Go to /forgot-password.php
2. Enter your registered email
3. See "Password reset link has been sent" message
4. Check email for "Password Reset Request" email
5. Click "Reset Password" link
6. Enter new password (twice)
7. See "Password reset successfully!"
8. Go to /login.php
9. Login with NEW password
10. You should see the dashboard!
```

---

## Common Mistakes

❌ **Wrong**: Using regular Gmail password instead of App Password
✅ **Right**: Using the 16-character App Password generated in Step 2

❌ **Wrong**: Not enabling 2-Factor Authentication first
✅ **Right**: Enable 2-Factor Auth BEFORE generating App Password

❌ **Wrong**: Forgetting to save code.php after updating credentials
✅ **Right**: Save the file after making changes

❌ **Wrong**: Looking in inbox only, not checking spam/junk
✅ **Right**: Check both inbox and spam folder for test emails

---

## Gmail Security Note

Your App Password is:
- Specific to this app only
- Can be revoked anytime
- Is different from your regular password
- Never requires you to disable security

You can safely use it in your app without compromising Gmail security.

---

## Still Having Issues?

1. **Clear browser cache** (Ctrl+Shift+Delete) and try again
2. **Verify database table exists** (run database_setup.sql)
3. **Check PHP error log** (XAMPP → logs folder)
4. **Test with a different Gmail account** (if you have one)
5. **Make sure 2-Factor is actually ENABLED** (sometimes it needs time to activate)

---

## What Gets Sent?

### Verification Email Contains:
- Greeting with user's name
- Verification link (unique token)
- Plain text link backup
- 24-hour expiration notice

### Password Reset Email Contains:
- Password reset link (unique token)
- Plain text link backup
- 1-hour expiration notice
- "If you didn't request this" warning

---

## Next Steps

1. Complete Gmail setup above
2. Update code.php with your credentials
3. Create database table (if not done)
4. Test registration → Should get verification email
5. Verify email → Should be able to login
6. Test forgot password → Should get reset email
7. Reset password → Should login with new password

**You're all set!** Your authentication system should now be fully functional with working emails.
