<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");


$id = intval($_GET['id']);

$sql = mysqli_query($conn, "
SELECT
    payments.*,
    invoices.invoice_no
FROM payments
INNER JOIN invoices
    ON invoices.id = payments.invoice_id
WHERE payments.invoice_id = '$id'
ORDER BY payments.id DESC
");

$data = [];
$sr = 1;
while($row = mysqli_fetch_assoc($sql)){

    $data[] = [
    $sr++,
    $row['invoice_no'],
    $row['gateway'],
    $row['payment_method'],
    $row['transaction_id'],
    $row['currency'],
    $row['amount'],
    strtoupper($row['status']),
    $row['failure_reason'],
    $row['paid_at'],
    '<a href="../controller/payment_receipt.php?id='.$row['id'].'" target="_blank" class="btn btn-primary btn-sm">View Receipt</a>'
];
}


header('Content-Type: application/json');
echo json_encode($data);