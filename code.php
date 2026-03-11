<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer-main/PHPMailer/Exception.php';
require 'phpmailer-main/PHPMailer/PHPMailer.php';
require 'phpmailer-main/PHPMailer/SMTP.php';

// Gmail SMTP Configuration
define('GMAIL_USERNAME', 'yourgmail@gmail.com');
define('GMAIL_PASSWORD', 'your_app_password');
define('SITE_URL', 'http://localhost/practicework1');

// Function to send email verification
function sendemail_verify($name, $email, $verify_token)
{
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = GMAIL_USERNAME;
        $mail->Password = GMAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(GMAIL_USERNAME, 'Instrumentalist Hub');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "Email Verification - Instrumentalist Hub";

        $verification_link = SITE_URL . "/verify-email.php?token=" . $verify_token;
        $mail->Body = "
            <html>
            <head><style>body{font-family: Arial, sans-serif;}</style></head>
            <body>
                <h2>Hello $name,</h2>
                <p>Thank you for registering with Instrumentalist Hub!</p>
                <p>Please verify your email address by clicking the link below:</p>
                <p><a href='$verification_link' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verify Email Address</a></p>
                <p>Or copy this link in your browser:</p>
                <p>$verification_link</p>
                <p>This link will expire in 24 hours.</p>
                <p>Best regards,<br>Instrumentalist Hub Team</p>
            </body>
            </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Function to send password reset email
function sendemail_reset($name, $email, $reset_token)
{
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = GMAIL_USERNAME;
        $mail->Password = GMAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(GMAIL_USERNAME, 'Instrumentalist Hub');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request - Instrumentalist Hub";

        $reset_link = SITE_URL . "/reset-password.php?token=" . $reset_token;
        $mail->Body = "
            <html>
            <head><style>body{font-family: Arial, sans-serif;}</style></head>
            <body>
                <h2>Hello $name,</h2>
                <p>We received a request to reset your password.</p>
                <p>Click the link below to reset your password:</p>
                <p><a href='$reset_link' style='background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Password</a></p>
                <p>Or copy this link in your browser:</p>
                <p>$reset_link</p>
                <p>This link will expire in 1 hour.</p>
                <p>If you did not request a password reset, please ignore this email.</p>
                <p>Best regards,<br>Instrumentalist Hub Team</p>
            </body>
            </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Registration Handler
if (isset($_POST['register_btn'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['status'] = "All fields are required!";
        $_SESSION['status_code'] = "error";
        header("Location: register.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format!";
        $_SESSION['status_code'] = "error";
        header("Location: register.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Passwords do not match!";
        $_SESSION['status_code'] = "error";
        header("Location: register.php");
        exit();
    }

    // Validate password strength (minimum 6 characters)
    if (strlen($password) < 6) {
        $_SESSION['status'] = "Password must be at least 6 characters long!";
        $_SESSION['status_code'] = "error";
        header("Location: register.php");
        exit();
    }

    // Check if email already exists
    $check_email_query = "SELECT email FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $check_email_query_run = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['status'] = "Email already registered! Please use another email or login.";
        $_SESSION['status_code'] = "error";
        header("Location: register.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate verification token
    $verify_token = md5(rand() . time());
    $created_at = date('Y-m-d H:i:s');

    // Insert user into database
    $query = "INSERT INTO users (name, phone, email, password, verify_token, created_at)
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $phone, $email, $hashed_password, $verify_token, $created_at);
    
    if (mysqli_stmt_execute($stmt)) {
        // Send verification email
        if (sendemail_verify($name, $email, $verify_token)) {
            $_SESSION['status'] = "Registration successful! A verification email has been sent to $email. Please verify your email to activate your account.";
            $_SESSION['status_code'] = "success";
        } else {
            $_SESSION['status'] = "Registration successful but we couldn't send verification email. Please contact support.";
            $_SESSION['status_code'] = "warning";
        }
        header("Location: register.php");
        exit();
    } else {
        $_SESSION['status'] = "Registration failed! Please try again.";
        $_SESSION['status_code'] = "error";
        header("Location: register.php");
        exit();
    }
}

// Forgot Password Handler
if (isset($_POST['forgot_password_btn'])) {
    $email = trim($_POST['email']);

    // Validation
    if (empty($email)) {
        $_SESSION['status'] = "Please enter your email address!";
        $_SESSION['status_code'] = "error";
        header("Location: forgot-password.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format!";
        $_SESSION['status_code'] = "error";
        header("Location: forgot-password.php");
        exit();
    }

    // Check if email exists
    $check_email_query = "SELECT id, name, email FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Generate reset token
        $reset_token = md5(rand() . time());
        $reset_token_expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update user with reset token
        $update_query = "UPDATE users SET reset_token = ?, reset_token_expire = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "ssi", $reset_token, $reset_token_expire, $user['id']);

        if (mysqli_stmt_execute($stmt)) {
            // Send reset password email
            if (sendemail_reset($user['name'], $user['email'], $reset_token)) {
                $_SESSION['status'] = "Password reset link has been sent to your email. Please check your inbox.";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "We couldn't send the reset email. Please try again later.";
                $_SESSION['status_code'] = "error";
            }
        }
    } else {
        // For security, don't reveal if email exists
        $_SESSION['status'] = "If this email is registered, you will receive a password reset link shortly.";
        $_SESSION['status_code'] = "success";
    }

    header("Location: forgot-password.php");
    exit();
}

// Reset Password Handler
if (isset($_POST['reset_password_btn'])) {
    $token = $_POST['token'];
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    if (empty($password) || empty($confirm_password)) {
        $_SESSION['status'] = "All fields are required!";
        $_SESSION['status_code'] = "error";
        header("Location: reset-password.php?token=$token");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Passwords do not match!";
        $_SESSION['status_code'] = "error";
        header("Location: reset-password.php?token=$token");
        exit();
    }

    // Validate password strength
    if (strlen($password) < 6) {
        $_SESSION['status'] = "Password must be at least 6 characters long!";
        $_SESSION['status_code'] = "error";
        header("Location: reset-password.php?token=$token");
        exit();
    }

    // Verify token exists and hasn't expired
    $check_token_query = "SELECT id FROM users WHERE reset_token = ? AND reset_token_expire > NOW() LIMIT 1";
    $stmt = mysqli_prepare($con, $check_token_query);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $update_query = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expire = NULL WHERE id = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user['id']);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = "Password reset successfully! You can now login with your new password.";
            $_SESSION['status_code'] = "success";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['status'] = "An error occurred. Please try again.";
            $_SESSION['status_code'] = "error";
            header("Location: reset-password.php?token=$token");
            exit();
        }
    } else {
        $_SESSION['status'] = "Invalid or expired reset link. Please request a new one.";
        $_SESSION['status_code'] = "error";
        header("Location: forgot-password.php");
        exit();
    }
}
?>
