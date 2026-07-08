<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");


$email = $_GET['email'];

$sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email' AND `remove` = '0'");

if(mysqli_num_rows($sql) > 0){
    echo "false";
}else{
    echo "true";
}

exit();
?>