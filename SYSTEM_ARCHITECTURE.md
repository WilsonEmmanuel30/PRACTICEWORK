# PHPMailer Authentication System - Architecture Documentation

## System Overview

This is a complete authentication system with email verification and password reset functionality built with PHP and PHPMailer. The system handles user registration, email verification, login, password recovery, and session management.

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                        USERS (Clients)                       │
└──────────────┬──────────────────────────────────────────────┘
               │
               ├─────────────────┐
               │                 │
               ▼                 ▼
        ┌──────────────┐   ┌──────────────┐
        │ register.php │   │  login.php   │
        └──────┬───────┘   └──────┬───────┘
               │                  │
               │                  │
               └────────┬─────────┘
                        │
                        ▼
                   ┌──────────────┐
                   │  code.php    │ (Business Logic)
                   └──────┬───────┘
                          │
        ┌─────────────────┼─────────────────┐
        │                 │                 │
        ▼                 ▼                 ▼
    ┌─────────┐    ┌──────────────┐    ┌──────────┐
    │   DB    │    │  PHPMailer   │    │ Sessions │
    │(MySQL)  │    │ (Gmail SMTP) │    │  (PHP)   │
    └─────────┘    └──────────────┘    └──────────┘
```

## Component Architecture

### 1. Frontend Layer (User Interface)

**Files**: `register.php`, `login.php`, `verify-email.php`, `forgot-password.php`, `reset-password.php`, `dashboard.php`

**Responsibilities**:
- Display user forms
- Collect user input
- Show validation feedback
- Display success/error messages
- Bootstrap-based responsive design

**Flow**:
```
User Input → Form Submission → code.php → Response Handling → Display Result
```

### 2. Business Logic Layer

**File**: `code.php`

**Responsibilities**:
- Input validation
- Database operations
- Password hashing/verification
- Token generation and validation
- Email sending
- Session management
- Error handling

**Functions**:
- `sendemail_verify($name, $email, $verify_token)` - Sends verification email
- `sendemail_reset($name, $email, $reset_token)` - Sends reset email
- Registration handler (`register_btn`)
- Login handler (`login_btn`)
- Forgot password handler (`forgot_password_btn`)
- Reset password handler (`reset_password_btn`)

### 3. Data Layer

**File**: `database_setup.sql`, accessed via `dbcon.php`

**Database Connection**: 
```
mysqli_connect("localhost", "root", "", "practicework")
```

**Table**: `users`
```
users
├── id (INT, PK, AI)
├── name (VARCHAR 255)
├── phone (VARCHAR 20)
├── email (VARCHAR 255, UNIQUE)
├── password (VARCHAR 255)
├── verify_token (VARCHAR 255)
├── is_verified (INT, DEFAULT 0)
├── reset_token (VARCHAR 255)
├── reset_token_expire (DATETIME)
├── created_at (DATETIME, DEFAULT CURRENT_TIMESTAMP)
├── updated_at (DATETIME, AUTO_UPDATE)
└── Indexes: email, verify_token, reset_token
```

### 4. Email Service Layer

**Handled by**: PHPMailer in `code.php`

**Configuration**:
- SMTP Host: smtp.gmail.com
- SMTP Port: 587
- Encryption: STARTTLS
- Authentication: Gmail App Password

**Email Types**:
1. Verification Email - Sent during registration
2. Reset Password Email - Sent on password recovery request

## Data Flow Diagrams

### Registration Flow

```
register.php
    ▼
User submits form
    ▼
code.php (register_btn handler)
    ├─ Validate input
    ├─ Check duplicate email
    ├─ Hash password
    ├─ Generate verify_token
    ├─ Insert into database
    ├─ Send verification email (PHPMailer)
    └─ Return success/error message
    ▼
register.php (displays message)
```

**Database Query**:
```sql
INSERT INTO users (name, phone, email, password, verify_token, created_at)
VALUES (?, ?, ?, ?, ?, ?)
```

### Email Verification Flow

```
verify-email.php (receives token in URL)
    ▼
Extract token from $_GET
    ▼
code.php (verify token in database)
    ├─ Query: SELECT FROM users WHERE verify_token = ?
    ├─ If found:
    │   ├─ Update: is_verified = 1
    │   ├─ Update: verify_token = NULL
    │   └─ Show success message
    └─ If not found:
        └─ Show error message
    ▼
verify-email.php (displays result)
    └─ Link to login.php
```

### Login Flow

```
login.php
    ▼
User submits email & password
    ▼
code.php (login_btn handler)
    ├─ Validate input
    ├─ Query: SELECT FROM users WHERE email = ?
    ├─ If user found:
    │   ├─ Check is_verified = 1
    │   ├─ Verify password with password_verify()
    │   ├─ If verified:
    │   │   ├─ Create session variables
    │   │   ├─ $_SESSION['user_id']
    │   │   ├─ $_SESSION['user_name']
    │   │   ├─ $_SESSION['user_email']
    │   │   └─ Redirect to dashboard.php
    │   └─ If not verified:
    │       └─ Show error message
    └─ If user not found:
        └─ Show error message
    ▼
dashboard.php OR login.php (with message)
```

### Forgot Password Flow

```
forgot-password.php
    ▼
User enters email
    ▼
code.php (forgot_password_btn handler)
    ├─ Validate email format
    ├─ Query: SELECT FROM users WHERE email = ?
    ├─ If found:
    │   ├─ Generate reset_token
    │   ├─ Set reset_token_expire = NOW() + 1 hour
    │   ├─ Update database
    │   └─ Send reset email with token link
    └─ Show generic success message (security)
    ▼
forgot-password.php (success message)
    └─ User checks email for reset link
```

### Password Reset Flow

```
Email link with token
    ▼
reset-password.php (receives token)
    ├─ Validate token in database
    ├─ Check token not expired
    └─ Show form if valid, error if not
    ▼
User submits new password
    ▼
code.php (reset_password_btn handler)
    ├─ Validate input
    ├─ Verify token & expiration again
    ├─ Hash new password
    ├─ Update password in database
    ├─ Clear reset_token
    ├─ Clear reset_token_expire
    └─ Redirect to login.php
    ▼
login.php (user logs in with new password)
```

### Session Flow

```
User logs in
    ▼
code.php creates session
    ├─ $_SESSION['user_id'] = user's ID
    ├─ $_SESSION['user_name'] = user's name
    └─ $_SESSION['user_email'] = user's email
    ▼
Protected pages (like dashboard.php)
    ├─ Check if $_SESSION['user_id'] exists
    ├─ If yes → Show page
    └─ If no → Redirect to login.php
    ▼
logout.php destroys session
    ├─ session_destroy()
    └─ Redirect to login.php
```

## Security Architecture

### 1. Input Validation Layer
- All user input is validated
- Email format check with `filter_var()`
- Required field validation
- String trimming to prevent spaces

### 2. SQL Injection Prevention
- All database queries use prepared statements
- `mysqli_prepare()` prepares query template
- `mysqli_stmt_bind_param()` binds parameters safely
- No string concatenation in queries

**Safe Pattern**:
```php
$query = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
```

### 3. Password Security
- Hashing with `password_hash()` (bcrypt)
- Verification with `password_verify()`
- Never stored in plain text
- Minimum 6 characters requirement

**Safe Pattern**:
```php
$hashed = password_hash($password, PASSWORD_DEFAULT);  // Hash
password_verify($input, $hashed);                       // Verify
```

### 4. Token Security
- Unique tokens generated for each action
- Token generation: `md5(rand() . time())`
- Tokens stored in database
- Tokens cleared after use
- Reset tokens expire after 1 hour

### 5. Session Security
- Native PHP `$_SESSION` array
- Session check on protected pages
- No sensitive data in sessions
- Sessions cleared on logout

**Safe Pattern**:
```php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
```

## Integration Points

### 1. Database Connection
```php
include('dbcon.php');
// Uses: $con (mysqli connection)
```

### 2. Email Service
```php
require 'phpmailer-main/PHPMailer/src/Exception.php';
require 'phpmailer-main/PHPMailer/src/PHPMailer.php';
require 'phpmailer-main/PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
```

### 3. Session Management
```php
session_start();
// Access via: $_SESSION['key']
```

## Error Handling Strategy

### 1. User-Friendly Errors
- Generic error messages for security
- "Invalid email or password" (doesn't reveal which field)
- "This email is registered" (prevents email enumeration)

### 2. Email Errors
- Try-catch blocks around email sending
- Returns true/false status
- User informed of sending failure
- Doesn't expose SMTP errors

### 3. Database Errors
- Query execution checks
- mysqli_stmt_execute() return value checked
- Error messages don't expose SQL syntax
- Development errors logged, not shown to user

### 4. Validation Errors
- Clear, actionable error messages
- Field-specific validation feedback
- Form data preserved (except password)
- Helpful hints (e.g., "Minimum 6 characters")

## Performance Optimization

### 1. Database Optimization
- Indexes on frequently searched columns
  - email (used in login, registration)
  - verify_token (used in verification)
  - reset_token (used in password reset)

### 2. Query Optimization
- Prepared statements cache query plans
- Only required fields selected
- LIMIT 1 used where applicable
- No N+1 queries

### 3. Session Optimization
- No database queries on page load
- Session data cached in memory
- Session data serialized by PHP
- Session file-based (can be extended to Redis)

### 4. Email Optimization
- Email sending is non-blocking
- PHPMailer has built-in error handling
- SMTP connection reused in code.php
- Timeout handling built-in

## Scalability Considerations

### 1. Horizontal Scaling
- Session storage can be moved to Redis
- Database can be moved to separate server
- Email service can use queue (Celery/RabbitMQ)
- Stateless request handling

### 2. Database Scaling
- Current schema supports millions of users
- Indexes allow fast lookups
- No complex joins
- Prepared statements optimize execution

### 3. Email Scaling
- Can implement email queue
- Can use background jobs
- Can use transactional email service (SendGrid, AWS SES)
- Current implementation is single-threaded

### 4. Session Scaling
- Move from file-based to Redis
- Use sticky sessions in load balancer
- Or use database-backed sessions
- No session dependency on user affinity needed

## Deployment Architecture

### Development
```
localhost:80/PRACTICEWORK/
├── PHP Files
├── Database (localhost:3306)
├── Email (Gmail SMTP)
└── Sessions (File-based)
```

### Production
```
example.com/
├── PHP Files (HTTPS)
├── Database (RDS/Cloud DB)
├── Email (SendGrid/AWS SES)
└── Sessions (Redis)
```

## File Dependencies

```
register.php
    ├─ includes/header.php
    ├─ includes/navbar.php
    ├─ includes/footer.php
    └─ code.php (form submission)

login.php
    ├─ includes/header.php
    ├─ includes/navbar.php
    ├─ includes/footer.php
    ├─ dbcon.php
    └─ code.php (form submission)

verify-email.php
    ├─ includes/header.php
    ├─ includes/navbar.php
    ├─ includes/footer.php
    └─ dbcon.php

forgot-password.php
    ├─ includes/header.php
    ├─ includes/navbar.php
    ├─ includes/footer.php
    └─ code.php (form submission)

reset-password.php
    ├─ includes/header.php
    ├─ includes/navbar.php
    ├─ includes/footer.php
    ├─ dbcon.php
    └─ code.php (form submission)

dashboard.php
    ├─ includes/header.php
    ├─ includes/navbar.php
    ├─ includes/footer.php
    └─ code.php (session check)

code.php
    ├─ dbcon.php
    ├─ PHPMailer (multiple classes)
    └─ Session management

logout.php
    └─ Session destruction
```

## Security Checklist

- ✅ SQL Injection Prevention (Prepared Statements)
- ✅ Password Hashing (bcrypt via password_hash)
- ✅ Email Verification (Token-based)
- ✅ Token Expiration (1 hour for reset)
- ✅ Session Management (PHP native)
- ✅ Input Validation (Format & Required)
- ✅ HTTPS Ready (SITE_URL configurable)
- ✅ Error Handling (User-friendly messages)
- ✅ CSRF Protection (Stateless, form-based)
- ✅ XSS Prevention (Output escaping with htmlspecialchars)

## Conclusion

This authentication system provides a complete, secure foundation for user management with email verification and password recovery. It follows PHP best practices and includes comprehensive security measures for protecting user data.

---

**Version**: 1.0
**Last Updated**: March 2025
**Architecture Type**: MVC-inspired (Separation of concerns)
**Tech Stack**: PHP 7.4+, MySQL, PHPMailer, Bootstrap
