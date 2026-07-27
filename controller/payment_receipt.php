<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Generate Payment Receipt PDF
 *
 * @param mysqli $conn
 * @param int $invoiceId
 * @return string|false
 */
function generateReceiptPDF($conn, $invoiceId)
{
    $invoiceId = (int)$invoiceId;

    // ================= FETCH INVOICE + CUSTOMER =================

    $invoiceQuery = mysqli_query($conn, "
        SELECT
            invoices.*,
            contacts.name,
            contacts.email
        FROM invoices
        INNER JOIN contacts
            ON contacts.id = invoices.contact_id
        WHERE invoices.id = '$invoiceId'
        LIMIT 1
    ");

    if (!$invoiceQuery || mysqli_num_rows($invoiceQuery) == 0) {
        return false;
    }

    $invoice = mysqli_fetch_assoc($invoiceQuery);

    // ================= FETCH PAYMENT =================

    $paymentQuery = mysqli_query($conn, "
        SELECT *
        FROM payments
        WHERE invoice_id = '$invoiceId'
          AND status = 'paid'
        ORDER BY id DESC
        LIMIT 1
    ");

    if (!$paymentQuery || mysqli_num_rows($paymentQuery) == 0) {
        return false;
    }

    $payment = mysqli_fetch_assoc($paymentQuery);

    // ================= RECEIPT NUMBER =================

    $receiptNo = "RCPT-" . str_pad($payment["id"], 5, "0", STR_PAD_LEFT);

    // ================= HTML =================

    $html = '

    <html>

    <head>

        <style>

            body{
                font-family: DejaVu Sans, sans-serif;
                font-size:14px;
                color:#333;
            }

            .container{
                border:1px solid #ccc;
                padding:20px;
            }

            h2{
                text-align:center;
                margin-bottom:20px;
            }

            table{
                width:100%;
                border-collapse:collapse;
            }

            td{
                padding:8px;
                border-bottom:1px solid #eee;
            }

            .label{
                width:40%;
                font-weight:bold;
            }

            .status{
                color:green;
                font-weight:bold;
            }

            .footer{
                text-align:center;
                margin-top:25px;
                font-size:13px;
            }

        </style>

    </head>

    <body>

    <div class="container">

        <h2>PAYMENT RECEIPT</h2>

        <table>

            <tr>
                <td class="label">Receipt No.</td>
                <td>'.$receiptNo.'</td>
            </tr>

            <tr>
                <td class="label">Invoice No.</td>
                <td>'.$invoice["invoice_no"].'</td>
            </tr>

            <tr>
                <td class="label">Customer</td>
                <td>'.$invoice["name"].'</td>
            </tr>

            <tr>
                <td class="label">Email</td>
                <td>'.$invoice["email"].'</td>
            </tr>

            <tr>
                <td class="label">Amount Paid</td>
                <td>'.$payment["currency"].' '.number_format($payment["amount"],2).'</td>
            </tr>

            <tr>
                <td class="label">Payment Gateway</td>
                <td>'.ucfirst($payment["gateway"]).'</td>
            </tr>

            <tr>
                <td class="label">Payment Method</td>
                <td>'.$payment["payment_method"].'</td>
            </tr>

            <tr>
                <td class="label">Transaction ID</td>
                <td>'.$payment["transaction_id"].'</td>
            </tr>

            <tr>
                <td class="label">Payment Date</td>
                <td>'.date("d M Y h:i A", strtotime($payment["paid_at"])).'</td>
            </tr>

            <tr>
                <td class="label">Status</td>
                <td class="status">'.strtoupper($payment["status"]).'</td>
            </tr>

        </table>

        <div class="footer">

            <p>Thank you for your payment!</p>

            <p>This is a computer-generated receipt.</p>

        </div>

    </div>

    </body>

    </html>

    ';

    // ================= PDF =================

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    return $dompdf->output();
}