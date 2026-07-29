<?php
require_once "../includes/api_auth.php";
include("../config/connection.php");

$email = $_POST['email'];
$id =  isset($_POST['id']) ? (int) $_POST['id'] : 0;
$sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email' AND `id` != '$id'");

if(mysqli_num_rows($sql) > 0){
    echo "false";
}else{
    echo "true";
}
exit();
?>