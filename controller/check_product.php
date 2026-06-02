<?php
session_start();

//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$product_code = $_POST['product_code'];
$product_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

$sql = mysqli_query($conn, "SELECT * FROM `products` WHERE `product_code`='$product_code' AND `id` != '$product_id'");
if(mysqli_num_rows($sql) > 0){
    echo "false";
}else{
    echo "true";
}

exit();


?>