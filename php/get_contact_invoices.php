<?php

include("../config/connection.php");

$id = intval($_GET['id']);

$sql = mysqli_query($conn,"
SELECT *
FROM invoices
WHERE contact_id='$id' AND `remove` = '0'
ORDER BY id DESC
");

$data = [];
$sr = 1;
while($row = mysqli_fetch_assoc($sql)){

    $data[] = [
        $sr++,
        $row['invoice_no'],
        $row['invoice_date'],
        $row['grand_total'],
        $row['status'],
        '<a href="php/view_invoice.php?id='.$row['id'].'" target="_blank" class="btn btn-primary btn-sm">View</a>'
    ];
}


header('Content-Type: application/json');
echo json_encode($data);