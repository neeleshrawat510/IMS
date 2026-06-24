<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
//conneciton setup
include("config/connection.php");
$invoice_id = $_POST['id'];

$deleteInvoice = mysqli_query($conn, "UPDATE `invoices` SET `remove` = '1' WHERE id='$invoice_id'");

if($deleteInvoice){
    echo "success";
}else{
    echo "failed";
}

?>