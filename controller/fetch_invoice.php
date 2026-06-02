<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}
//conneciton setup
include("../config/connection.php");

$sql = mysqli_query($conn, "
    SELECT 
        invoices.id,
        contacts.fname,
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
");

$row = mysqli_fetch_array($sql);
echo json_encode($row);

?>