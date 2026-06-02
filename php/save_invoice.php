<?php
session_start();
include("../config/connection.php");

$contact_id   = $_POST['contact_id'];
$invoice_no   = $_POST['invoice_no'];
$invoice_date = $_POST['invoice_date'];
$due_date     = $_POST['due_date'];

$subtotal     = $_POST['subtotal'];
$tax_total    = $_POST['tax_total'];
$grand_total  = $_POST['grand_total'];

// Get items
$product_id  = $_POST['product_id'];
$description = $_POST['description'];
$qty         = $_POST['qty'];
$price       = $_POST['price'];
$tax         = $_POST['tax'];
$amount      = $_POST['amount'];

// Save invoice (main record)
$insertInvoice = mysqli_query($conn, "
    INSERT INTO invoices 
    (contact_id, invoice_no, invoice_date, due_date, subtotal, tax_total, grand_total)
    VALUES 
    ('$contact_id', '$invoice_no', '$invoice_date', '$due_date', '$subtotal', '$tax_total', '$grand_total')
");

// Get invoice ID (important for linking items)
$invoice_id = mysqli_insert_id($conn);

// Save each product (loop)
$count = count($product_id);

for ($i = 0; $i < $count; $i++) {

    $p_id   = $product_id[$i];
    $desc   = $description[$i];
    $q      = $qty[$i];
    $pr     = $price[$i];
    $tx     = $tax[$i];
    $amt    = $amount[$i];

    mysqli_query($conn, "
        INSERT INTO invoice_items 
        (invoice_id, product_id, description, qty, price, tax, amount)
        VALUES 
        ('$invoice_id', '$p_id', '$desc', '$q', '$pr', '$tx', '$amt')
    ");
}

//  Response
if ($insertInvoice) {
    echo "success";
} else {
    echo "failed";
}
?>