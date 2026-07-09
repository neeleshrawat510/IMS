<?php

require_once "../includes/api_auth.php";

//indian timezone
date_default_timezone_set('Asia/Kolkata');


include("../config/connection.php");
include("../controller/role_check.php");

requireRole("Admin");



$product_id    = $_POST['id'];

$product_code = trim(mysqli_real_escape_string($conn, $_POST['product_code'] ?? ''));
$product_name = trim(mysqli_real_escape_string($conn, $_POST['product_name'] ?? ''));
$cost_price = trim(mysqli_real_escape_string($conn, $_POST['cost_price'] ?? ''));
$selling_price = trim(mysqli_real_escape_string($conn, $_POST['selling_price'] ?? ''));
$tax = trim(mysqli_real_escape_string($conn, $_POST['tax'] ?? ''));
$todayDate = date('Y-m-d H:i:s');


$update = mysqli_query($conn, "UPDATE `products` SET 
                                        `product_code` = '$product_code',
                                        `product_name` = '$product_name',
                                        `cost_price` = '$cost_price',
                                        `selling_price` = '$selling_price',
                                        `tax` = '$tax',
                                        `updated_at` = '$todayDate'
                                        WHERE `id` = '$product_id'");

if($update){
    echo "success";
}else{
    echo "failed";
}
?>