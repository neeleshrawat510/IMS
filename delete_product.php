<?php

require_once "includes/auth_check.php";

//connection setup
include("config/connection.php");


$product_id = $_POST['id'];

$delete = mysqli_query($conn, "DELETE FROM `products` WHERE `id` = '$product_id'");

if($delete){
    echo "success";
}else{
    echo "failed";
}

?>