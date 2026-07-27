<?php

function sendPaymentEmail(
    $toEmail,
    $toName,
    $invoiceNo,
    $status,
    $transactionId = null,
    $failureReason = null,
    $payUrl = null,
    $pdfOutput = null
) {

    $apiKey = getenv('BREVO_API_KEY');
    
    if (!$apiKey) {
        error_log("Brevo API Key not found.");
        return false;
    }

    $attachment = [
        [
            "name" => "Receipt_" . $invoiceNo . ".pdf",
            "content" => base64_encode($pdfOutput)
        ]
    ];
    
    if ($status === "paid") {

        $subject = "Payment Received - Invoice #{$invoiceNo}";

        $htmlContent = "
            <h2>Hello {$toName},</h2>

            <p>We have successfully received your payment.</p>

            <table cellpadding='6'>
                <tr>
                    <td><strong>Invoice No:</strong></td>
                    <td>{$invoiceNo}</td>
                </tr>

                <tr>
                    <td><strong>Status:</strong></td>
                    <td style='color:green;'>Paid</td>
                </tr>

                <tr>
                    <td><strong>Transaction ID:</strong></td>
                    <td>{$transactionId}</td>
                </tr>
            </table>

            <br>

            <p>Thank you for your payment.</p>

            <p><strong>Baseline IT</strong></p>
        ";

    } else {

        $subject = "Payment Failed - Invoice #{$invoiceNo}";

        $htmlContent = "
            <h2>Hello {$toName},</h2>

            <p>Unfortunately your payment could not be processed.</p>

            <table cellpadding='6'>
                <tr>
                    <td><strong>Invoice No:</strong></td>
                    <td>{$invoiceNo}</td>
                </tr>

                <tr>
                    <td><strong>Status:</strong></td>
                    <td style='color:red;'>Failed</td>
                </tr>

                <tr>
                    <td><strong>Reason:</strong></td>
                    <td>{$failureReason}</td>
                </tr>
            </table>

            <br>
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
                    Try Payment Again
                </a>
            </p>
            <p>If the button doesn't work, copy and paste this link into your browser:</p>

<p>{$payUrl}</p>

            <p><strong>Baseline IT</strong></p>
        ";
    }

    
    $data = [

        "sender" => [
            "name" => "Baseline IT",
            "email" => "neeleshrawat510@gmail.com"
        ],

        "to" => [[
            "email" => $toEmail,
            "name" => $toName
        ]],

        "subject" => $subject,

        "htmlContent" => $htmlContent,
    ];

    if ($pdfOutput !== null) {
    $data["attachment"] = $attachment;
}


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

        error_log(curl_error($ch));

        curl_close($ch);

        return false;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return ($httpCode >= 200 && $httpCode < 300);
}