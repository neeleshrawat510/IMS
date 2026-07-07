<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");

$product_id = $_POST['id'];

$archiveProduct = mysqli_query($conn, "UPDATE `products` SET `remove` = '1' WHERE `id` = '$product_id'");

if($archiveProduct){
    echo "success";
}else{
    echo "failed";
}

?>