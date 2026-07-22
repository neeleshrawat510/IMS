<?php
//connection setup

include("../config/connection.php");
$token = $_POST['token'];

$password = md5($_POST['password']);

// Verify token again
$checkToken = mysqli_query($conn, "
    SELECT * FROM users
    WHERE reset_token = '$token'
    AND token_expiry > NOW()
");

if (mysqli_num_rows($checkToken) == 0) {
    echo "failed";
    exit;
}

$updatePass = mysqli_query($conn, "UPDATE `users` SET `password` = '$password', `reset_token` = NULL, `token_expiry` = NULL WHERE `reset_token` = '$token'");

if($updatePass && mysqli_affected_rows($conn) > 0){
    echo "success";
}else{
    echo "failed";
}
?>
