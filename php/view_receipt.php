<?php

include("../config/connection.php");
require_once "../controller/payment_receipt.php";


if (!isset($_GET['id'])) {
    die("Invalid request");
}

$invoiceId = (int)$_GET['id'];

$pdf = generateReceiptPDF($conn, $invoiceId);

if ($pdf === false) {
    die("Receipt not found");
}

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=\"Receipt.pdf\"");
header("Content-Length: " . strlen($pdf));

echo $pdf;
exit;