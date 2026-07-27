<?php

require_once 'vendor/autoload.php';
include 'config/connection.php';

use Stripe\Webhook;
use Stripe\Stripe;
use Stripe\PaymentIntent;

// Read Stripe request body
$payload = @file_get_contents('php://input');


// Get Stripe signature
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

//stripe secret key
Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

// Webhook secret from Railway variables
$endpointSecret = getenv('STRIPE_WEBHOOK_SECRET');


try {

    $event = Webhook::constructEvent(
        $payload,
        $sigHeader,
        $endpointSecret
    );
} catch (\UnexpectedValueException $e) {

    http_response_code(400);
    exit("Invalid Payload");
} catch (\Stripe\Exception\SignatureVerificationException $e) {

    http_response_code(400);
    exit("Invalid Signature");
}



// Handle successful payment
if ($event->type === 'checkout.session.completed') {


    $session = $event->data->object;


    // Get invoice details from metadata
    $invoiceId = $session->metadata->invoice_id;
    $checkoutSessionId = $session->id;

    $paymentIntent = PaymentIntent::retrieve($session->payment_intent);

    $paymentIntentId = $paymentIntent->id;

    $transactionId = $paymentIntent->latest_charge ?? null;

    $paymentMethod = $paymentIntent->payment_method_types[0] ?? 'card';

    // Store  Stripe response
    $gatewayResponse = mysqli_real_escape_string(
        $conn,
        json_encode([
            'session' => $session,
            'payment_intent' => $paymentIntent
        ])
    );


    //Update payments table

    $updatePaymentSql = "
UPDATE payments
SET
    gateway_payment_id = '$paymentIntentId',
    transaction_id = " . ($transactionId ? "'$transactionId'" : "NULL") . ",
    status = 'paid',
    payment_method = '$paymentMethod',
    gateway_response = '$gatewayResponse',
    failure_reason = NULL,
    paid_at = NOW(),
    updated_at = NOW()
WHERE checkout_session_id = '$checkoutSessionId'
";

    mysqli_query($conn, $updatePaymentSql);


    //Update invoice payment status

    mysqli_query($conn, "
UPDATE invoices
SET
    payment_status='Paid'
WHERE id='$invoiceId'
");

}

//failed payment

if ($event->type == 'payment_intent.payment_failed') {

    $paymentIntent = $event->data->object;
    
    $invoiceId = $paymentIntent->metadata->invoice_id;
    
    $paymentIntentId = $paymentIntent->id;

    $transactionId = $paymentIntent->latest_charge ?? null;

    $failureReason = '';

    if (!empty($paymentIntent->last_payment_error)) {

        $failureReason = mysqli_real_escape_string(
            $conn,
            $paymentIntent->last_payment_error->message
        );
    }

    $gatewayResponse = mysqli_real_escape_string(
        $conn,
        json_encode($paymentIntent)
    );

    $sql = "
    UPDATE payments
    SET
        gateway_payment_id='$paymentIntentId',
        transaction_id=" . ($transactionId ? "'$transactionId'" : "NULL") . ",
        status='failed',
        gateway_response='$gatewayResponse',
        failure_reason='$failureReason',
        updated_at=NOW()
    WHERE invoice_id='$invoiceId'
       OR checkout_session_id IN (
            SELECT id FROM (
                SELECT checkout_session_id AS id
                FROM payments
                WHERE gateway_payment_id='$paymentIntentId'
            ) x
       )
    ";

    mysqli_query($conn, $sql);
}


// Expired session
if ($event->type === 'checkout.session.expired') {

    $session = $event->data->object;

    $checkoutSessionId = $session->id;

    mysqli_query($conn,"
        UPDATE payments
        SET
            status='failed',
            updated_at=NOW()
        WHERE checkout_session_id='$checkoutSessionId'
    ");

}

// Always return success to Stripe
http_response_code(200);

echo "Webhook received";
