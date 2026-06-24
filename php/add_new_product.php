<?php
session_start();

//connection setup
include("../config/connection.php");

//Indian Timezone
date_default_timezone_set("ASIA/KOLKATA");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$product_code = trim(mysqli_real_escape_string($conn, $_POST['product_code'] ?? ''));
$product_name = trim(mysqli_real_escape_string($conn, $_POST['product_name'] ?? ''));
$cost_price = trim(mysqli_real_escape_string($conn, $_POST['cost_price'] ?? ''));
$selling_price = trim(mysqli_real_escape_string($conn, $_POST['selling_price'] ?? ''));
$tax = trim(mysqli_real_escape_string($conn, $_POST['tax'] ?? ''));
$todayDate = date('Y-m-d H:i:s');

$insert = mysqli_query($conn, "INSERT INTO `products` (`product_code`,`product_name`,`cost_price`,`selling_price`,`tax`,`created_at`) VALUES ('$product_code','$product_name','$cost_price','$selling_price','$tax','$todayDate')");

if($insert){
    echo "success";
}else{
    echo "failed";
}

?>