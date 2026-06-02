<?php
session_start();

include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$fname = trim(mysqli_real_escape_string($conn, $_POST['fname']  ??  ''));
$lname = trim(mysqli_real_escape_string($conn, $_POST['lname']  ??  ''));
$number = trim(mysqli_real_escape_string($conn, $_POST['number']  ??  ''));
$email = trim(mysqli_real_escape_string($conn, $_POST['email']  ??  ''));
$address = trim(mysqli_real_escape_string($conn, $_POST['address']  ??  ''));


$insert = mysqli_query($conn, "INSERT INTO `contacts` (`fname`, `lname`, `number`, `email`, `address`) VALUES('$fname', '$lname', '$number', '$email', '$address')");

if($insert){
    echo "success";
}else{
    echo "failed";
}
?>