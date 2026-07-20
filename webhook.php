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

    echo "<pre>";
    print_r($session);
    exit;
}