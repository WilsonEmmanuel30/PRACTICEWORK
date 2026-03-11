# PHPMailer Authentication System - Verification Checklist

## Pre-Implementation Checklist

Before testing, ensure you have completed these setup steps:

- [ ] Downloaded/cloned the PRACTICEWORK project
- [ ] Have PHP 7.4+ installed (check with `php -v`)
- [ ] Have MySQL running locally
- [ ] Have phpMyAdmin or MySQL CLI available
- [ ] Have a Gmail account with 2-Factor Authentication enabled
- [ ] Have generated a Gmail App Password (16 characters)
- [ ] Have XAMPP/WAMP server running

## Database Setup Checklist

- [ ] Created the `users` table using `database_setup.sql`
- [ ] Verified table has all columns: id, name, phone, email, password, verify_token, is_verified, reset_token, reset_token_expire, created_at, updated_at
- [ ] Verified indexes are created on email, verify_token, reset_token
- [ ] Email column is set as UNIQUE
- [ ] is_verified defaults to 0
- [ ] created_at and updated_at have proper defaults

## Configuration Checklist

- [ ] Updated GMAIL_USERNAME in code.php with your Gmail address
- [ ] Updated GMAIL_PASSWORD in code.php with your 16-character App Password
- [ ] Updated SITE_URL in code.php (or left as localhost)
- [ ] Verified PHPMailer path is correct: `phpmailer-main/PHPMailer/src/`
- [ ] Verified dbcon.php connects to correct database

## File Implementation Checklist

### Created Files:
- [ ] `/database_setup.sql` exists
- [ ] `/verify-email.php` exists
- [ ] `/forgot-password.php` exists
- [ ] `/reset-password.php` exists
- [ ] `/logout.php` exists
- [ ] `/SETUP_GUIDE.md` exists
- [ ] `/QUICK_START.md` exists
- [ ] `/IMPLEMENTATION_SUMMARY.md` exists
- [ ] `/VERIFICATION_CHECKLIST.md` exists (this file)

### Modified Files:
- [ ] `/code.php` contains registration handler
- [ ] `/code.php` contains login handler
- [ ] `/code.php` contains forgot password handler
- [ ] `/code.php` contains reset password handler
- [ ] `/code.php` contains sendemail_verify() function
- [ ] `/code.php` contains sendemail_reset() function
- [ ] `/register.php` has confirm password field
- [ ] `/register.php` has success/error messages
- [ ] `/login.php` has login form action
- [ ] `/login.php` has forgot password link
- [ ] `/dashboard.php` has session protection
- [ ] `/dashboard.php` has logout button

## Feature Testing Checklist

### Test 1: Registration
- [ ] Navigate to http://localhost/PRACTICEWORK/register.php
- [ ] Form loads without errors
- [ ] Try submitting empty form → Error message shown
- [ ] Enter valid data with matching passwords → Success message
- [ ] Check email inbox for verification email
- [ ] Verification email contains link to verify-email.php
- [ ] Try registering same email again → Duplicate error
- [ ] Try registering with mismatched passwords → Error message
- [ ] Try registering with password < 6 chars → Error message
- [ ] Try registering with invalid email → Error message

### Test 2: Email Verification
- [ ] Click verification link from email
- [ ] See success message "Email verification successful"
- [ ] See button "Proceed to Login"
- [ ] Click on button → Redirects to login.php
- [ ] Try accessing verify-email.php without token → Error message
- [ ] Try accessing with invalid token → Error message

### Test 3: Login
- [ ] Navigate to http://localhost/PRACTICEWORK/login.php
- [ ] Form loads without errors
- [ ] Try submitting empty form → Error message
- [ ] Try logging in before email verification → "Please verify email" message
- [ ] After email verification, login with correct credentials
- [ ] See success message with username
- [ ] Redirected to dashboard.php
- [ ] Try login with wrong password → Error message
- [ ] Try login with non-existent email → Error message

### Test 4: Dashboard Access Control
- [ ] When logged in, dashboard shows user info
- [ ] Dashboard displays correct name
- [ ] Dashboard displays correct email
- [ ] Logout button is present and visible
- [ ] Try accessing /dashboard.php without logging in → Redirected to login
- [ ] Try accessing with modified session → Redirected to login

### Test 5: Logout
- [ ] Click logout button on dashboard
- [ ] Redirected to login.php
- [ ] Session is cleared
- [ ] Try accessing dashboard → Redirected to login
- [ ] Try going back with browser → Cannot access dashboard

### Test 6: Forgot Password
- [ ] Navigate to http://localhost/PRACTICEWORK/forgot-password.php
- [ ] Form loads without errors
- [ ] Try submitting empty form → Error message
- [ ] Enter registered email → Success message
- [ ] Check email inbox for reset email
- [ ] Reset email contains link to reset-password.php
- [ ] Enter non-existent email → Still shows success (security)
- [ ] Try accessing forgot-password.php multiple times → Different tokens generated

### Test 7: Password Reset
- [ ] Click reset link from email
- [ ] reset-password.php loads with form
- [ ] Form is only shown if token is valid
- [ ] Try invalid token in URL → Error message
- [ ] Try expired token (>1 hour old) → Error message
- [ ] Enter matching passwords that are 6+ chars → Success message
- [ ] Redirected to login.php
- [ ] Try logging in with new password → Successful login
- [ ] Old password no longer works
- [ ] Try using same reset link again → Error (token cleared)
- [ ] Try mismatched passwords → Error message
- [ ] Try password < 6 chars → Error message

### Test 8: Session Management
- [ ] After login, refresh page → Still logged in
- [ ] Open in new tab → Session accessible (same browser)
- [ ] Close browser and reopen → Session lost (new session)
- [ ] Multiple users can login simultaneously
- [ ] User A can't see User B's info

### Test 9: Email Delivery
- [ ] Check Gmail "Sent" folder for emails sent
- [ ] Check if using correct Gmail address
- [ ] Verify 2-Factor authentication is enabled
- [ ] Verify App Password is correct (not regular password)
- [ ] Check Gmail security logs for app access

## Security Testing Checklist

### SQL Injection Prevention:
- [ ] Try registering with email: `admin'--` → Should fail safely
- [ ] Try login with email: `' OR '1'='1` → Should fail safely
- [ ] Try with special characters in password → Should work normally
- [ ] Database should have no errors in error logs

### Password Security:
- [ ] Passwords are hashed in database (not plain text)
- [ ] Check database: All passwords start with `$2y$` (bcrypt hash)
- [ ] password_verify() successfully validates correct passwords
- [ ] password_verify() fails with incorrect passwords

### Token Security:
- [ ] Each registration generates unique verify_token
- [ ] Each password reset generates unique reset_token
- [ ] Tokens are different each time (random generation)
- [ ] verify_token is cleared after verification
- [ ] reset_token is cleared after password reset
- [ ] reset_token_expire time is set to future (+1 hour)

### Email Verification:
- [ ] Users cannot login without email verification
- [ ] is_verified field is 0 until verified
- [ ] is_verified field is 1 after successful verification

### Session Security:
- [ ] Sessions contain necessary user info (id, name, email)
- [ ] Sessions do not contain password or sensitive info
- [ ] Session variables accessible only to authenticated users

## Performance Testing Checklist

- [ ] Registration completes in < 2 seconds
- [ ] Email sending completes in < 5 seconds
- [ ] Login completes in < 1 second
- [ ] Verification check completes in < 1 second
- [ ] Password reset completes in < 2 seconds
- [ ] Dashboard loads in < 1 second
- [ ] Multiple registrations don't cause slowdown
- [ ] Email sending doesn't block other operations

## Browser Compatibility Checklist

- [ ] Test on Chrome → Works
- [ ] Test on Firefox → Works
- [ ] Test on Safari → Works
- [ ] Test on Edge → Works
- [ ] Mobile browser (iPhone) → Works
- [ ] Mobile browser (Android) → Works
- [ ] Form validation works on all browsers
- [ ] Alert messages display correctly

## Error Handling Checklist

- [ ] All error messages are user-friendly
- [ ] No PHP errors are displayed to users
- [ ] Database connection errors handled gracefully
- [ ] Email sending failures show appropriate message
- [ ] Invalid tokens show clear error messages
- [ ] Expired tokens show clear error messages
- [ ] Form validation shows helpful error messages
- [ ] Redirects work correctly after errors

## Documentation Checklist

- [ ] README or index page explains the system
- [ ] QUICK_START.md is clear and concise
- [ ] SETUP_GUIDE.md covers all steps
- [ ] IMPLEMENTATION_SUMMARY.md documents all features
- [ ] Gmail setup instructions are clear
- [ ] Database setup instructions are clear
- [ ] File structure is documented
- [ ] Troubleshooting section is helpful

## Final Deployment Checklist

Before deploying to production:

- [ ] All tests from this checklist pass
- [ ] No hardcoded credentials visible in code
- [ ] HTTPS is enabled (if on production server)
- [ ] Email sending works reliably
- [ ] Database backups are configured
- [ ] Error logs are configured
- [ ] Production Gmail credentials are set
- [ ] SITE_URL is updated to production domain
- [ ] Database is optimized with indexes
- [ ] Security headers are configured
- [ ] CORS settings are appropriate

## Troubleshooting Verification

If issues occur, verify:

1. **Registration Issues**:
   - [ ] All form fields are required
   - [ ] Email format validation works
   - [ ] Password confirmation is required
   - [ ] Database table exists
   - [ ] Database connection works
   - [ ] PHPMailer is correctly included

2. **Email Issues**:
   - [ ] Gmail username is correct (with @gmail.com)
   - [ ] Gmail App Password is 16 characters
   - [ ] 2-Factor authentication is enabled
   - [ ] Using App Password, not regular password
   - [ ] SMTP host is smtp.gmail.com
   - [ ] Port is 587
   - [ ] STARTTLS encryption is enabled
   - [ ] PHPMailer version is correct

3. **Login Issues**:
   - [ ] User email is verified (is_verified = 1)
   - [ ] Password is correct
   - [ ] Database connection works
   - [ ] Session is properly configured

4. **Reset Password Issues**:
   - [ ] Token exists in database
   - [ ] Token has not expired
   - [ ] reset_token_expire is in the future
   - [ ] Password confirmation matches

## Sign-Off

Once all checks are complete and tested:

- [ ] Feature implementation is complete
- [ ] All tests pass
- [ ] Documentation is complete
- [ ] System is ready for use
- [ ] No security issues found
- [ ] No performance issues found

---

**Verification Date**: _______________
**Verified By**: _____________________
**Status**: _____ PASS / _____ FAIL

If FAIL, document issues and repeat relevant test sections.

**Last Updated**: March 2025
**Version**: 1.0
