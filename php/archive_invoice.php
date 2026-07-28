<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");


$invoiceId = $_POST['id'];

$archive = mysqli_query($conn, "UPDATE `invoices` SET `remove` = '1' WHERE `id` = '$invoiceId'");

if($archive){
    echo "success";
}else{
    echo "failed";
}

?>