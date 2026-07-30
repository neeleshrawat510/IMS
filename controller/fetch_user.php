<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");

$userId = $_GET['id'];

$sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = '$userId'");

$contact = mysqli_fetch_array($sql);

header('Content_type: application/json');
echo json_encode($contact);
?>