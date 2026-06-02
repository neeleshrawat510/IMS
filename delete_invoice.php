<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
//conneciton setup
include("config/connection.php");
$invoice_id = $_GET['id'];

$deleteInvoice = mysqli_query($conn, "DELETE FROM `invoices` WHERE id='$invoice_id'");

if($deleteInvoice){
    echo "success";
    header("location: manage_invoice.php");
}else{
    echo "failed";
}
?>