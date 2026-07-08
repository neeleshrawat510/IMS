<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");

$contactID = $_GET['id'];

$sql = mysqli_query($conn, "SELECT * FROM `contacts` WHERE `id` = '$contactID' AND `remove` = '0'");

$contact = mysqli_fetch_array($sql);

header('Content_type: application/json');
echo json_encode($contact);
?>