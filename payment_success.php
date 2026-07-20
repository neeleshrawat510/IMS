<?php

require_once 'vendor/autoload.php';
include 'config/connection.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

// Set Stripe Secret Key
Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

// Check Session ID
if (!isset($_GET['session_id']) || empty($_GET['session_id'])) {
    die("Invalid Request.");
}

$sessionId = $_GET['session_id'];

try {

    // Get Checkout Session
    $session = Session::retrieve($sessionId);

} catch (Exception $e) {

    die("Unable to verify payment.");

}

// Get invoice id from metadata
$invoiceId = $session->metadata->invoice_id;

// Fetch invoice
$sql = "SELECT invoice_no, grand_total, payment_status
        FROM invoices
        WHERE id = '$invoiceId'";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Invoice not found.");
}

$invoice = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html>

<head>

    <title>Payment Successful</title>

</head>

<body>

    <h2>Payment Successful</h2>

    <p>
        <strong>Invoice No:</strong>
        <?php echo $invoice['invoice_no']; ?>
    </p>

    <p>
        <strong>Amount:</strong>
        ₹<?php echo number_format($invoice['grand_total'], 2); ?>
    </p>

    <p>
        <strong>Status:</strong>
        <?php echo ucfirst($invoice['payment_status']); ?>
    </p>

    <p>
        Thank you for your payment.
    </p>

</body>

</html>