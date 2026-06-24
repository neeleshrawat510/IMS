<?php
session_start();

//setting up connection with DB
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}



$email = $_GET['email'];

$sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email' AND `remove` = '0'");

if(mysqli_num_rows($sql) > 0){
    echo "false";
}else{
    echo "true";
}

exit();
?>