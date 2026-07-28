<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");


$invoiceId = $_POST['id'];

$unarchive = mysqli_query($conn, "UPDATE `invoices` SET `remove` = '0' WHERE `id` = '$invoiceId'");

if($unarchive){
    echo "success";
}else{
    echo "failed";
}

?>