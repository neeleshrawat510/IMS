<?php

require_once "../includes/api_auth.php";
require_once "../config/connection.php";
require_once "../controller/invoice_pdf.php";


if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = (int)$_GET['id'];

$pdf = generateInvoicePdf($conn, $id);

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=\"invoice.pdf\"");
header("Content-Length: " . strlen($pdf));

echo $pdf;
exit;