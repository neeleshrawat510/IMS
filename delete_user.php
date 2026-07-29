<?php

require_once "includes/auth_check.php";

//connection setup
include("config/connection.php");

$userId = $_POST['id'];

$delete = mysqli_query($conn, "DELETE FROM `users` WHERE `id` = '$userId'");

if($delete){
    echo "success";
}else{
    echo "failed";
}

?>