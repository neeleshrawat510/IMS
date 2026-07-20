<?php

require_once 'vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

if (!isset($_GET['session_id'])) {
    die("Invalid Request.");
}

$sessionId = $_GET['session_id'];

$session = Session::retrieve($sessionId);

echo "<pre>";
print_r($session);
exit;