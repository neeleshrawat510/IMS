<?php
session_start();

include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$name = trim(mysqli_real_escape_string($conn, $_POST['name']  ??  ''));
$number = trim(mysqli_real_escape_string($conn, $_POST['number']  ??  ''));
$email = trim(mysqli_real_escape_string($conn, $_POST['email']  ??  ''));
$company = trim(mysqli_real_escape_string($conn, $_POST['company']  ??  ''));
$gst = trim(mysqli_real_escape_string($conn, $_POST['gst']  ??  ''));
$address = trim(mysqli_real_escape_string($conn, $_POST['address']  ??  ''));
$todayDate = date('Y-m-d H:i:s');


$insert = mysqli_query($conn, "INSERT INTO `contacts` (`name`, `number`, `email`,`company`, `gst`, `address`, `created_at`) VALUES('$name', '$number', '$email', '$company', '$gst', '$address', '$todayDate')");

if($insert){
    echo "success";
}else{
    echo "failed";
}
?>