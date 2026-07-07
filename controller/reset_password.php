<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//connection setup
include("../config/connection.php");

//Indian timezone
date_default_timezone_set('Asia/Kolkata');


//POST Data
$email = trim($_POST['email']);

$checkMail = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email'");

if (mysqli_num_rows($checkMail) == '0') {
    echo "failed";
    exit;
}

$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

$updateToken = mysqli_query($conn, "UPDATE `users` SET `reset_token` = '$token', `token_expiry` = '$expiry' WHERE `email` = '$email'");

if (!$updateToken) {
    echo "failed";
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();

    $mail->Host = getenv("SMTP_HOST");
    $mail->SMTPAuth = true;
    $mail->Username = getenv("SMTP_USER");
    $mail->Password = getenv("SMTP_PASS");

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = getenv("SMTP_PORT");

    $mail->setFrom(
        getenv("MAIL_FROM"),
        getenv("MAIL_FROM_NAME")
    );

    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Password Reset Request";

    $link = getenv('APP_URL') . "/update_password.php?token=" . $token;
    $mail->Body = '
                <div style="max-width:600px;margin:auto;padding:30px;font-family:Arial,sans-serif;border:1px solid #ddd;border-radius:8px;">
                    <h2 style="color:#0d6efd;">Invoice Management System</h2>

                    <p>Hello,</p>

                    <p>We received a request to reset your password.</p>

                    <p style="text-align:center;margin:30px 0;">
                        <a href="' . $link . '" style="
                            background:#0d6efd;
                            color:#fff;
                            text-decoration:none;
                            padding:12px 24px;
                            border-radius:5px;
                            display:inline-block;">
                            Reset Password
                        </a>
                    </p>

                    <p>This link is valid for <strong>1 hour</strong>.</p>

                    <p>If you did not request a password reset, you can safely ignore this email.</p>

                    <hr>

                    <small>
                        Invoice Management System
                    </small>

                </div>';

    $mail->send();

    echo "success";
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}

?>