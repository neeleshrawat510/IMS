<?php
session_start();

//connection setup
include("../config/connection.php");

$id = $_GET['id'];


$sql = mysqli_query($conn, "
SELECT
    i.id,
    i.invoice_no,
    i.invoice_date,
    i.due_date,
    i.subtotal,
    i.tax_total,
    i.grand_total,

    c.fname,
    c.lname,
    c.number,

    ii.qty,
    ii.description,
    ii.price,
    ii.tax,
    ii.amount,

    p.product_name,
    p.product_code

FROM invoices i

INNER JOIN contacts c
    ON i.contact_id = c.id

INNER JOIN invoice_items ii
    ON ii.invoice_id = i.id

INNER JOIN products p
    ON ii.product_id = p.id

WHERE i.id = $id
");

$items = [];

while($row = mysqli_fetch_assoc($sql)){
    $items[] = $row;
}
// header('Content-Type: application/json');
echo json_encode($items);

?>