<?php
//connection setup

include("../config/connection.php");
$token = $_POST['token'];

$password = md5($_POST['password']);

$updatePass = mysqli_query($conn, "UPDATE `users` SET `password` = '$password', `reset_token` = 'NULL', `token_expiry` = 'NULL' WHERE `reset_token` = '$token'");

if($updatePass){
    echo "success";
}else{
    echo "failed";
}
?>
