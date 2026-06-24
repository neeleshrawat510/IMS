<?php
//connection setup
include("../config/connection.php");

//Indian timezone
date_default_timezone_set('Asia/Kolkata');


//POST Data
$email = trim($_POST['email']);

$checkMail = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email'");

if(mysqli_num_rows($checkMail) == '0'){
echo "failed";
exit;
}

$token = md5(rand());
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
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = '13f154c474bcfb';
    $mail->Password = 'e784d9ad6a3a1a';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 2525;

    $mail->setFrom('no-reply@yourapp.com', 'Your App');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';

    $link = "http://localhost/invoice_management_system/update_password.php?token=" . $token;

    $mail->Body = '
        <div style="max-width:400px;margin:auto;padding:20px;border:1px solid #ddd;border-radius:8px;font-family:Arial;">
            <p>Reset your password using the link below:</p>
            <p>
                <a href="' . $link . '" style="display:inline-block;padding:10px 15px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;">
                    Click here to reset password
                </a>
            </p>   
            <p>This link will expire in 1 hour</p>
        </div>
    ';

    $mail->send();
    
    echo "success";
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}

?>
