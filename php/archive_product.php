<?php
session_start();
//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$product_id = $_POST['id'];

$archiveProduct = mysqli_query($conn, "UPDATE `products` SET `remove` = '1' WHERE `id` = '$product_id'");

if($archiveProduct){
    echo "success";
}else{
    echo "failed";
}

?>