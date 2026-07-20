<?php

require_once 'vendor/autoload.php';
include 'config/connection.php';

use Stripe\Webhook;

// Read raw request body
$payload = @file_get_contents('php://input');

// Stripe Signature
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

// Webhook Secret
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

if ($event->type == 'checkout.session.completed') {
    
    $session = $event->data->object;

    $invoiceId = $session->metadata->invoice_id;
    $checkoutSessionId = $session->i;
    $paymentIntentId = $session->payment_intent;

}

$gatewayResponse = mysqli_real_escape_string(
    $conn,
    json_encode($session)
);

$updatePaymentSql = "
UPDATE payments
SET
    gateway_payment_id = '$paymentIntentId',
    transaction_id = '$paymentIntentId',
    status = 'paid',
    payment_method = 'card',
    gateway_response = '$gatewayResponse',
    paid_at = NOW()
WHERE checkout_session_id = '$checkoutSessionId'
";

$result = mysqli_query($conn, $updatePaymentSql);

if (!$result) {
    http_response_code(500);
    exit(mysqli_error($conn));
}