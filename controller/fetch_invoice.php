<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");

$sql = mysqli_query($conn, "
    SELECT 
        invoices.id,
        contacts.name,
        contacts.number,
        invoices.invoice_no,
        invoices.invoice_date,
        invoices.due_date,
        invoices.description,
        invoices.qty,
        invoices.price,
        invoices.tax,
        invoices.amount,
        invoices.subtotal,
        invoices.tax_total,
        invoices.grand_total
        
    FROM invoices 
    INNER JOIN contacts 
        ON invoices.contact_id = contacts.id
        WHERE invoices.remove = 0
");

$row = mysqli_fetch_array($sql);
echo json_encode($row);

?>