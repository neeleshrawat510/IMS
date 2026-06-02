<?php
session_start();
//connection setup
include("config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$product_id = $_GET['id'];

$delete = mysqli_query($conn, "DELETE FROM `products` WHERE `id` = '$product_id'");

if($delete){
    echo "success";
    header("location: manage_product.php");
}else{
    echo "failed";
}

?>