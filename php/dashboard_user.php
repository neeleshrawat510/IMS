<?php

require_once "../includes/api_auth.php";
include("../config/connection.php");

$id = $_SESSION['user_id'];  //get id from session

$sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`= '$id' AND remove ='0' ");

$user = mysqli_fetch_array($sql);

echo json_encode($user);
?>
