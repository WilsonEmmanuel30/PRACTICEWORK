<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer-main/PHPMailer/Exception.php';
require 'phpmailer-main/PHPMailer/PHPMailer.php';
require 'phpmailer-main/PHPMailer/SMTP.php';

$error = '';
$success = '';

// Check if form submitted
if (isset($_POST['test_email'])) {
    $gmail_address = trim($_POST['gmail_address']);
    $gmail_password = trim($_POST['gmail_password']);
    $test_recipient = trim($_POST['test_recipient']);

    // Validation
    if (empty($gmail_address) || empty($gmail_password) || empty($test_recipient)) {
        $error = 'All fields are required!';
    } else {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $gmail_address;
            $mail->Password = $gmail_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->Timeout = 10;
            $mail->SMTPDebug = 0;

            $mail->setFrom($gmail_address, 'Test Email');
            $mail->addAddress($test_recipient);
            $mail->isHTML(true);
            $mail->Subject = 'PHPMailer Test Email';
            $mail->Body = '
                <html>
                <body style="font-family: Arial, sans-serif;">
                    <h2>Gmail & PHPMailer Configuration Test</h2>
                    <p><strong>Status:</strong> SUCCESS! Your Gmail is properly configured.</p>
                    <p>This email confirms that:</p>
                    <ul>
                        <li>Your Gmail address is correct</li>
                        <li>Your App Password is correct</li>
                        <li>SMTP connection works</li>
                        <li>Email sending is functional</li>
                    </ul>
                    <p style="color: green; font-weight: bold;">✓ Your authentication system will now send emails correctly!</p>
                    <p style="margin-top: 20px; color: #888; font-size: 12px;">
                        Test sent at: ' . date('Y-m-d H:i:s') . '
                    </p>
                </body>
                </html>
            ';

            if ($mail->send()) {
                $success = '✓ TEST EMAIL SENT SUCCESSFULLY! Check your inbox for the test email.';
            }
        } catch (Exception $e) {
            $error = 'FAILED: ' . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gmail Configuration Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        input[type="text"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76,175,80,0.3);
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        .info-box h3 {
            margin-top: 0;
            color: #1976D2;
        }
        .info-box p {
            margin: 5px 0;
            color: #333;
            font-size: 14px;
        }
        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gmail & PHPMailer Configuration Test</h1>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <strong>Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="gmail_address">Gmail Address:</label>
                <input type="email" id="gmail_address" name="gmail_address" required placeholder="your.email@gmail.com">
                <div class="help-text">Your full Gmail address (e.g., john.doe@gmail.com)</div>
            </div>

            <div class="form-group">
                <label for="gmail_password">App Password (16 characters):</label>
                <input type="text" id="gmail_password" name="gmail_password" required placeholder="abcd efgh ijkl mnop">
                <div class="help-text">The 16-character password from Google App Passwords (not your regular password!)</div>
            </div>

            <div class="form-group">
                <label for="test_recipient">Test Email Recipient:</label>
                <input type="email" id="test_recipient" name="test_recipient" required placeholder="your.email@gmail.com">
                <div class="help-text">Where to send the test email (usually your own Gmail)</div>
            </div>

            <button type="submit" name="test_email">Test Gmail Configuration</button>
        </form>

        <div class="info-box">
            <h3>How to Get Your App Password:</h3>
            <p>1. Go to <strong>https://myaccount.google.com/security</strong></p>
            <p>2. Enable <strong>2-Step Verification</strong> (if not already enabled)</p>
            <p>3. Go to <strong>https://myaccount.google.com/apppasswords</strong></p>
            <p>4. Select "Mail" and your device type</p>
            <p>5. Copy the <strong>16-character password</strong> shown</p>
            <p>6. Paste it above (including spaces)</p>
        </div>

        <div class="info-box" style="background-color: #fff3cd; border-left-color: #ffc107; margin-top: 20px;">
            <h3 style="color: #856404;">What This Test Does:</h3>
            <p>This tool tests if your Gmail credentials are working correctly by sending an actual email using your configuration. If it succeeds, your authentication system emails will work!</p>
        </div>
    </div>
</body>
</html>
