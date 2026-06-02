<?php
//session start
session_start();

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}
//connection
include("../config/connection.php");

$id = $_SESSION['user_id'];  //get id from session

$sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `id`= '$id'");

$user = mysqli_fetch_array($sql);

echo json_encode($user);
?>
