<?php

require_once '../../../vendor/autoload.php';
require_once '../../../config/connection.php';

use Stripe\Stripe;
use Stripe\Webhook;

Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

$payload = file_get_contents("php://input");