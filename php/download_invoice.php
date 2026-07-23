<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
require_once "../controller/invoice_pdf.php";


if (!isset($_GET['id'])) {
    die("Invalid request");
}

$invoiceId = (int)$_GET['id'];

$pdf = generateInvoicePDF($conn, $invoiceId);

if ($pdf === false) {
    die("Invoice not found");
}

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"Invoice_" . $invoiceId . ".pdf\"");
header("Content-Length: " . strlen($pdf));

echo $pdf;
exit;