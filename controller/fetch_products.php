<?php
session_start();

//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$product_id = $_GET['id'];

$sql = mysqli_query($conn, "SELECT * FROM `products` WHERE `id`= '$product_id' AND `remove` = '0'");
$row = mysqli_fetch_array($sql);

echo json_encode($row);
?>