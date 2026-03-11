<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer-main/PHPMailer/Exception.php';
require 'phpmailer-main/PHPMailer/PHPMailer.php';
require 'phpmailer-main/PHPMailer/SMTP.php';

function sendemail_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'yourgmail@gmail.com';
    $mail->Password = 'your_app_password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('yourgmail@gmail.com', 'Instrumentalist Hub');
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = "Verify Email Address";

    $mail->Body = "
        <h2>Hello $name</h2>
        <a href='http://localhost/PRACTICEWORK/verify-email.php?token=$verify_token'>
        Click here to verify your email
        </a>
    ";

    $mail->send();
}

if (isset($_POST['register_btn'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_token = md5(rand());
    $created_at = date('Y-m-d H:i:s');

      sendemail_verify($name,$email,$verify_token);
      echo "sent or not";

    // $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    // $check_email_query_run = mysqli_query($con, $check_email_query);

    // if(mysqli_num_rows($check_email_query_run) > 0)
    // {
    //     $_SESSION['status'] = "Email already exists";
    //     header("Location: register.php");
    //     exit();
    // }
    // else
    // {
    //     $query = "INSERT INTO users
    //     (name,phone,email,password,verify_token,created_at)
    //     VALUES
    //     ('$name','$phone','$email','$password','$verify_token','$created_at')";

    //     $query_run = mysqli_query($con, $query);

    //     if($query_run)
    //     {
    //         sendemail_verify($name,$email,$verify_token);
    //         $_SESSION['status'] = "Registration successful! Verify your email.";
    //     }
    //     else
    //     {
    //         $_SESSION['status'] = "Registration Failed";
    //     }

    //     header("Location: register.php");
    //     exit();
    // }
}
?>