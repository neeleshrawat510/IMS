<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");
include("../controller/role_check.php");

requireRole("Admin");

$product_id = $_POST['id'];

$delete = mysqli_query($conn, "UPDATE `products` SET `remove` = '0' WHERE `id` = '$product_id'");

if($delete){
    echo "success";
}else{
    echo "failed";
}

?>