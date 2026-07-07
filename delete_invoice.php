<?php

require_once "includes/auth_check.php";

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