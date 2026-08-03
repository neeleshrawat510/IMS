<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../includes/api_auth.php";
include("../config/connection.php");
require_once "../includes/api_auth.php";
include("../config/connection.php");

$id = $_SESSION['user_id'];  //get id from session

$sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`= '$id'");

$user = mysqli_fetch_array($sql);

echo json_encode($user);

?>
