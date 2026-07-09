<?php

require_once "../includes/api_auth.php";
//Indian Timezone
date_default_timezone_set("ASIA/KOLKATA");

include("../config/connection.php");



$product_code = trim(mysqli_real_escape_string($conn, $_POST['product_code'] ?? ''));
$product_name = trim(mysqli_real_escape_string($conn, $_POST['product_name'] ?? ''));
$cost_price = trim(mysqli_real_escape_string($conn, $_POST['cost_price'] ?? ''));
$selling_price = trim(mysqli_real_escape_string($conn, $_POST['selling_price'] ?? ''));
$tax = trim(mysqli_real_escape_string($conn, $_POST['tax'] ?? ''));
$todayDate = date('Y-m-d H:i:s');
$created_by = $_SESSION['user_name'];

$insert = mysqli_query($conn, "INSERT INTO `products` (`product_code`,`product_name`,`cost_price`,`selling_price`,`tax`,`created_at`,`created_by`) VALUES ('$product_code','$product_name','$cost_price','$selling_price','$tax','$todayDate','$created_by')");

if($insert){
    echo "success";
}else{
    echo "failed";
}

?>