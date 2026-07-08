<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");

$product_id = $_GET['id'];

$sql = mysqli_query($conn, "SELECT * FROM `products` WHERE `id`= '$product_id' AND `remove` = '0'");
$row = mysqli_fetch_array($sql);

echo json_encode($row);
?>