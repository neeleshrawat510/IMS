<?php

function sendUserCredentials($toEmail, $userName, $plainPassword)
{
    $apiKey = getenv('BREVO_API_KEY');

    if (!$apiKey) {
        error_log("Brevo API Key not found.");
        return false;
    }

    $loginUrl = getenv('APP_URL');

    $data = [
        "sender" => [
            "name" => "Baseline IT",
            "email" => "neeleshrawat510@gmail.com" // Your verified sender
        ],

        "to" => [
            [
                "email" => $toEmail,
                "name" => $userName
            ]
        ],

        "subject" => "Your Account Has Been Created",

        "htmlContent" => "
            <h2>Hello {$userName},</h2>

            <p>Your account has been created successfully.</p>

            <h3>Login Credentials</h3>

            <p>
                <strong>Email:</strong> {$toEmail}<br>
                <strong>Temporary Password:</strong> {$plainPassword}
            </p>

            <p>
                <a href='{$loginUrl}'
                style='display:inline-block;
                       padding:12px 24px;
                       background:#0d6efd;
                       color:#ffffff;
                       text-decoration:none;
                       border-radius:6px;
                       font-weight:bold;'>
                    Login Now
                </a>
            </p>

            <p>
                Please change your password after logging in for the first time.
            </p>

            <br>

            <p>Regards,<br><strong>Baseline IT</strong></p>
        "
    ];

    $ch = curl_init("https://api.brevo.com/v3/smtp/email");

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "api-key: {$apiKey}",
            "content-type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log("Brevo CURL Error: " . curl_error($ch));
        curl_close($ch);
        return false;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        return true;
    }

    error_log("Brevo Error ({$httpCode}): " . $response);

    return false;
}