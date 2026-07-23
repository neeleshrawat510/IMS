<?php

function sendInvoiceEmail($toEmail, $toName, $invoiceNo, $invoicePublicToken, $pdfOutput)
{
    $apiKey = getenv('BREVO_API_KEY');

    if (!$apiKey) {
        error_log("Brevo API Key not found.");
        return false;
    }


    $attachment = [
        [
            "name" => "Invoice_" . $invoiceNo . ".pdf",
            "content" => base64_encode($pdfOutput)
        ]
    ];

    $payUrl = getenv('APP_URL') . "/pay.php?token=" . $invoicePublicToken;

    $data = [
        "sender" => [
            "name" => "Baseline IT",
            "email" => "neeleshrawat510@gmail.com"
        ],

        "to" => [
            [
                "email" => $toEmail,
                "name" => $toName
            ]
        ],

        "subject" => "Invoice #" . $invoiceNo,

        "htmlContent" => "
            <h2>Hello {$toName},</h2>

            <p>Your invoice has been generated successfully.</p>

            <p><strong>Invoice Number:</strong> {$invoiceNo}</p>

<p>Please find your invoice attached.</p>

<p>
    <a href='{$payUrl}'
       style='
            display:inline-block;
            padding:12px 24px;
            background:#635BFF;
            color:#ffffff;
            text-decoration:none;
            border-radius:6px;
            font-weight:bold;
       '>
        Pay Now
    </a>
</p>

<p>If the button doesn't work, copy and paste this link into your browser:</p>

<p>{$payUrl}</p>

            <br>

            <p>Thank you.</p>

            <p><strong>Baseline IT</strong></p>
        ",

        "attachment" => $attachment
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

    // cURL failed
    if (curl_errno($ch)) {
        error_log("Brevo CURL Error: " . curl_error($ch));
        curl_close($ch);
        return false;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $result = json_decode($response, true);

    if ($httpCode >= 200 && $httpCode < 300) {
        return true;
    }

    error_log("Brevo Error ({$httpCode}): " . $response);

    return false;
}