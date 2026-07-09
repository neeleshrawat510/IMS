<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");


$name = trim(mysqli_real_escape_string($conn, $_POST['name']  ??  ''));
$number = trim(mysqli_real_escape_string($conn, $_POST['number']  ??  ''));
$email = trim(mysqli_real_escape_string($conn, $_POST['email']  ??  ''));
$company = trim(mysqli_real_escape_string($conn, $_POST['company']  ??  ''));
$gst = trim(mysqli_real_escape_string($conn, $_POST['gst']  ??  ''));
$address = trim(mysqli_real_escape_string($conn, $_POST['address']  ??  ''));
$todayDate = date('Y-m-d H:i:s');
$created_by = $_SESSION['user_name'];


$insert = mysqli_query($conn, "INSERT INTO `contacts` (`name`, `number`, `email`,`company`, `gst`, `address`, `created_at`, `created_by`) VALUES('$name', '$number', '$email', '$company', '$gst', '$address', '$todayDate', '$created_by')");

if($insert){
    echo "success";
}else{
    echo "failed";
}
?>