<?php

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

$link = getenv("APP_URL") . "/update_password.php?token=" . $token;

$data = [

    "sender" => [
        "name" => "Invoice Management System",
        "email" => getenv("MAIL_FROM")
    ],

    "to" => [
        [
            "email" => $email
        ]
    ],

    "subject" => "Password Reset Request",

    "htmlContent" => '
    <div style="max-width:600px;margin:auto;padding:30px;font-family:Arial,sans-serif;border:1px solid #ddd;border-radius:8px;">

        <h2 style="color:#0d6efd;">
            Invoice Management System
        </h2>

        <p>Hello,</p>

        <p>We received a request to reset your password.</p>

        <p style="text-align:center;margin:30px 0;">

            <a href="' . $link . '"
               style="
               background:#0d6efd;
               color:white;
               padding:12px 24px;
               text-decoration:none;
               border-radius:5px;
               display:inline-block;">

               Reset Password

            </a>

        </p>

        <p>
            This link is valid for
            <strong>1 hour</strong>.
        </p>

        <p>
            If you did not request this,
            you can ignore this email.
        </p>

    </div>'
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "accept: application/json",
    "api-key: " . getenv("BREVO_API_KEY"),
    "content-type: application/json"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($httpCode == 201) {

    echo "success";

} else {

    echo "HTTP Code: " . $httpCode . "<br><br>";

    echo "<pre>";
    print_r(json_decode($response, true));
    echo "</pre>";

}
?>