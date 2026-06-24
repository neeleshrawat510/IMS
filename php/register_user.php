<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}
//setting up connection with DB
include("../config/connection.php");


//input fields with mysql injection
$name = trim(mysqli_real_escape_string($conn, $_POST['name']) ?? '');
$number = trim(mysqli_real_escape_string($conn, $_POST['number']) ?? '');
$email = trim(mysqli_real_escape_string($conn, $_POST['email']) ?? '');
$password = md5($_POST['password']) ?? '';

$insert = mysqli_query($conn, "INSERT INTO `users` (`name`, `number`, `email`, `password`) VALUES ('$name','$number','$email','$password')");

if($insert){
    echo "success";
}else{
    echo "failed";
}
?>