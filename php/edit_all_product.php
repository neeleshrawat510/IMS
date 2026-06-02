<?php
session_start();

include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}


$product_id    = $_POST['id'];

$product_code = $_POST['product_code'];
$product_name = $_POST['product_name'];
$cost_price = $_POST['cost_price'];
$selling_price = $_POST['selling_price'];

$update = mysqli_query($conn, "UPDATE `products` SET 
                                        `product_code` = '$product_code',
                                        `product_name` = '$product_name',
                                        `cost_price` = '$cost_price',
                                        `selling_price` = '$selling_price'
                                        WHERE `id` = '$product_id'");

if($update){
    echo "success";
}else{
    echo "failed";
}
?>