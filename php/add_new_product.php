<?php
session_start();

//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$product_code = $_POST['product_code'];
$product_name = $_POST['product_name'];
$cost_price = $_POST['cost_price'];
$selling_price = $_POST['selling_price'];

$insert = mysqli_query($conn, "INSERT INTO `products` (`product_code`,`product_name`,`cost_price`,`selling_price`) VALUES ('$product_code','$product_name','$cost_price','$selling_price')");

if($insert){
    echo "success";
}else{
    echo "failed";
}

?>