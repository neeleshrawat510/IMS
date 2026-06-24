<?php
include("../config/connection.php");

header('Content-Type: application/json');

$id = $_GET['id'];

// Invoice header
$invoiceQuery = mysqli_query($conn, "
    SELECT i.*, c.name 
    FROM invoices i
    LEFT JOIN contacts c ON c.id = i.contact_id
    WHERE i.id = '$id'
");

$invoice = mysqli_fetch_assoc($invoiceQuery);

// Invoice items
$itemQuery = mysqli_query($conn, "
    SELECT ii.*, p.product_code, p.product_name 
    FROM invoice_items ii
    LEFT JOIN products p ON p.id = ii.product_id
    WHERE ii.invoice_id = '$id'
");

$items = [];

while ($row = mysqli_fetch_assoc($itemQuery)) {
    $items[] = $row;
}

// response
echo json_encode([
    "invoice" => $invoice,
    "items" => $items
]);
exit;