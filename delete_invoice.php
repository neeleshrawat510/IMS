<?php

require_once "includes/auth_check.php";

//conneciton setup
include("config/connection.php");
include("controller/role_check.php");

requireRole("Admin");

$invoice_id = $_POST['id'];

$deleteInvoice = mysqli_query($conn, "UPDATE `invoices` SET `remove` = '1' WHERE id='$invoice_id'");

if($deleteInvoice){
    echo "success";
}else{
    echo "failed";
}

?>