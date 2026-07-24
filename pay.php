<?php

require_once 'vendor/autoload.php';
include "config/connection.php";

use Stripe\Stripe;
use Stripe\Checkout\Session;

// Check token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid payment link.");
}

$token = trim($_GET['token']);

// Get Invoice
$getInvoiceSql = "SELECT * FROM invoices WHERE invoice_public_token = '$token'";
$result = mysqli_query($conn, $getInvoiceSql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Invalid payment link.");
}

$invoice = mysqli_fetch_assoc($result);


// Check payment status
if ($invoice['payment_status'] == 'Paid') {
    die("This invoice has already been paid.");
}

// Check invoice status
if ($invoice['status'] == 'Cancelled') {
    die("This invoice has been cancelled.");
}

// Check amount
if ($invoice['grand_total'] <= 0) {
    die("Invalid invoice amount.");
}

// Set Stripe Secret Key
Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

// Check existing pending payment session

$paymentQuery = "
    SELECT checkout_session_id 
    FROM payments
    WHERE invoice_id = '{$invoice['id']}'
    AND status = 'pending'
    ORDER BY id DESC
    LIMIT 1
";

$paymentResult = mysqli_query($conn, $paymentQuery);

if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {

    $payment = mysqli_fetch_assoc($paymentResult);

    if (!empty($payment['checkout_session_id'])) {


        try {

            $existingSession = Session::retrieve(
                $payment['checkout_session_id']
            );

            // Redirect to existing checkout session
            if (
                $existingSession->status === 'open' &&
                !empty($existingSession->url)
            ) {

                header("Location: " . $existingSession->url);
                exit;
            }
        } catch (Exception $e) {

            // If session invalid/expired,
            // create a new one below

        }
    }
}


try {

    // Create Checkout Session
    $session = Session::create([
        
        'mode' => 'payment',

        'success_url' => getenv('APP_URL') . '/payment_success.php?session_id={CHECKOUT_SESSION_ID}',

        'cancel_url' => getenv('APP_URL') . '/payment_cancel.php',
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'inr',

                    'product_data' => [
                        'name' => 'Invoice #' . $invoice['invoice_no']
                    ],

                    // Stripe accepts amount in paise
                    'unit_amount' => (int) round($invoice['grand_total'] * 100)
                ],

                'quantity' => 1
            ]
        ],

        'client_reference_id' => $invoice['id'],

        'metadata' => [
            'invoice_id' => $invoice['id'],
            'invoice_no' => $invoice['invoice_no']
        ]
    ]);
} catch (Exception $e) {
    die($e->getMessage());
}

echo "<pre>";
var_dump($session->payment_intent);
exit;
// Save Payment Record
$insertPaymentSql = "INSERT INTO payments
(
    `invoice_id`,
    `gateway`,
    `checkout_session_id`,
    `amount`,
    `currency`,
    `status`
)

VALUES
(
    '{$invoice['id']}',
    'stripe',
    '{$session->id}',
    '{$invoice['grand_total']}',
    'INR',
    'pending'
)";

$result = mysqli_query($conn, $insertPaymentSql);

if (!$result) {
    die(mysqli_error($conn));
}

// Redirect Customer
header("Location: " . $session->url);
exit;
