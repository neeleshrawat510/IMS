<?php

require_once 'vendor/autoload.php';
include 'config/connection.php';

use Stripe\Webhook;


// Read Stripe request body
$payload = @file_get_contents('php://input');


// Get Stripe signature
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';


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

    $paymentIntentId = $session->payment_intent;


    // Store complete Stripe response
    $gatewayResponse = mysqli_real_escape_string(
        $conn,
        json_encode($session)
    );


    //Update payments table
    

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


    $paymentResult = mysqli_query($conn, $updatePaymentSql);


    if (!$paymentResult) {

        http_response_code(500);
        exit(mysqli_error($conn));

    }



    //Update invoice payment status
    

    $updateInvoiceSql = "
        UPDATE invoices
        SET
            payment_status = 'paid'
        WHERE id = '$invoiceId'
    ";


    $invoiceResult = mysqli_query($conn, $updateInvoiceSql);


    if (!$invoiceResult) {

        http_response_code(500);
        exit(mysqli_error($conn));

    }


}


// Always return success to Stripe
http_response_code(200);

echo "Webhook received";