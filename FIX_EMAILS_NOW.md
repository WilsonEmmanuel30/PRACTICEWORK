# Fix Email Issues - Quick Action Plan

## THE PROBLEM
Your registration and password reset emails are NOT being sent because Gmail credentials are not configured.

## THE SOLUTION
Follow these 5 steps in order (takes 10 minutes):

---

## STEP 1: Enable 2-Factor Authentication (5 minutes)

**Required**: Gmail REQUIRES 2-Factor Auth to work with PHPMailer.

1. Go to: https://myaccount.google.com/security
2. Find **"How you sign in to Google"** section
3. Click **"2-Step Verification"**
4. Click **"Get Started"** button
5. Verify with your phone number
6. Complete the setup
7. You'll see a ✓ checkmark when done

---

## STEP 2: Generate App Password (3 minutes)

**Important**: This is different from your regular Gmail password!

1. Go to: https://myaccount.google.com/apppasswords
   (You must have 2-Factor Auth enabled first)
2. Select dropdown **"Select the app"** → Choose **"Mail"**
3. Select dropdown **"Select the device"** → Choose **"Windows Computer"** (or your device)
4. Click **"Generate"**
5. Google shows a 16-character password in a yellow box
6. **COPY THIS ENTIRE PASSWORD** (it looks like: `abcd efgh ijkl mnop`)

---

## STEP 3: Update code.php (2 minutes)

1. Open file: `C:\xamppp\htdocs\practicework1\code.php`
2. Find lines 13-14 (near the top):
```php
define('GMAIL_USERNAME', 'yourgmail@gmail.com');
define('GMAIL_PASSWORD', 'your_app_password');
```

3. Replace with YOUR details:
```php
define('GMAIL_USERNAME', 'your.email@gmail.com');     // Your Gmail address
define('GMAIL_PASSWORD', 'abcd efgh ijkl mnop');      // 16-char App Password from Step 2
```

4. **SAVE THE FILE** (Ctrl+S)

**Important Notes:**
- Replace `your.email@gmail.com` with your ACTUAL Gmail
- Replace `abcd efgh ijkl mnop` with your ACTUAL 16-char password
- Include the spaces in the password
- Don't use your regular Gmail password!

---

## STEP 4: Test Gmail Configuration (2 minutes)

Visit this page to test if Gmail is working:

**http://localhost/practicework1/test_gmail.php**

1. Enter your Gmail address
2. Enter your 16-character App Password
3. Enter your email address (to send test email to yourself)
4. Click **"Test Gmail Configuration"**
5. Check your email for test message

If you see "✓ TEST EMAIL SENT SUCCESSFULLY!", your Gmail is working!

---

## STEP 5: Test Full Registration (2 minutes)

Now test the actual system:

1. Go to: http://localhost/practicework1/register.php
2. Fill in registration:
   - Name: Your Name
   - Phone: 1234567890
   - Email: **YOUR email address**
   - Password: password123
   - Confirm Password: password123
3. Click **"REGISTER NOW"**
4. You should see: "Registration successful! A verification email has been sent"
5. **Check your email inbox** (and spam folder) for verification email
6. Click the verification link
7. You should see: "Email verification successful!"
8. Go to login page and login
9. You should see your dashboard!

---

## VERIFICATION CHECKLIST

- [ ] 2-Factor Authentication ENABLED on your Gmail account
- [ ] App Password GENERATED from myaccount.google.com/apppasswords
- [ ] code.php UPDATED with Gmail address (line 13)
- [ ] code.php UPDATED with 16-char App Password (line 14)
- [ ] code.php FILE SAVED
- [ ] test_gmail.php TEST PASSED (test email received)
- [ ] Registration form WORKING
- [ ] Verification email RECEIVED
- [ ] Verification link CLICKED
- [ ] Login WORKING
- [ ] Dashboard DISPLAYING

---

## TROUBLESHOOTING

### Email Not Received After Registration?
1. Check SPAM/JUNK folder
2. Check email address is correct
3. Run test_gmail.php to verify Gmail works
4. Check code.php has correct credentials

### test_gmail.php Shows Error?
1. Verify 2-Factor is ENABLED (not just turned on, but ACTIVE)
2. Wait 5 minutes after generating App Password
3. Verify no spaces before/after Gmail address
4. Verify 16-character App Password is complete

### "Email already exists" error?
1. Use a different email address
2. Or delete the user from database and try again

### "Invalid or expired reset link" on password reset?
1. Reset links expire in 1 hour
2. Request a new password reset link
3. Check email address is correct

---

## IMPORTANT NOTES

✓ Using App Password is SECURE - Gmail recommends it  
✓ You can revoke this password anytime from Google Account settings  
✓ This password only works for this app  
✓ Never use your regular Gmail password in code  

---

## STILL HAVING ISSUES?

1. **Read GMAIL_SETUP.md** - Comprehensive troubleshooting guide
2. **Check database table exists** - Run database_setup.sql
3. **Verify localhost URL** - Should be http://localhost/practicework1
4. **Clear browser cache** - Ctrl+Shift+Delete then refresh

---

## SUCCESS INDICATORS

When everything works, you should see:
1. ✓ Registration form submits successfully
2. ✓ Verification email arrives in inbox
3. ✓ Verification link works
4. ✓ Can login after verification
5. ✓ Forgot password sends email
6. ✓ Password reset link works
7. ✓ Can login with new password

---

**Expected Time to Complete: 10-15 minutes**

Start with STEP 1 now!
