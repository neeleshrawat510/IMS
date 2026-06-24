<?php
session_start();

// connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$sql = mysqli_query($conn, "
    SELECT 
        invoices.id,
        contacts.name,
        contacts.number,
        invoices.invoice_date,
        invoices.invoice_no,
        invoices.status,
        invoices.grand_total
    FROM invoices 
    INNER JOIN contacts 
        ON invoices.contact_id = contacts.id
        WHERE invoices.remove = '0'
");

$data = [];
$sr = 1;
if (mysqli_num_rows($sql) > 0) {

    while ($row = mysqli_fetch_array($sql)) {
    $data[] = [
                $row['id'],
                $sr++,
                $row['invoice_no'],
                $row['name'],
                $row['invoice_date'],
                $row['grand_total'],
                $row['status'],
                    '<a href="php/view_invoice.php?id=' . $row['id'] . '" target="_blank" class="btn btn-success btn-sm me-1">
                        <i class="bi bi-eye"></i> 
                    </a>
                    <a href="edit_invoice.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> 
                    </a>
                    <a href="php/download_invoice.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">
                        <i class="bi bi-download"></i> 
                    </a>
                    <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="' . $row['id'] . '">                          <i class="bi bi-trash"></i>
                    </a>'
                ];
    }

}
header('Content-Type: application/json');
echo json_encode($data);
?>