<?php
// session start
session_start();

include("../config/connection.php");


$email = trim(mysqli_real_escape_string($conn, $_POST['email']));
$password = md5($_POST['password']);

$sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");

if(mysqli_num_rows($sql) > 0){
    $user = mysqli_fetch_array($sql);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    echo "success";
}else{
    echo "failed";
}

?>