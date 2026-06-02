<?php
session_start();

//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$contactID = $_GET['id'];

$sql = mysqli_query($conn, "SELECT * FROM `contacts` WHERE `id` = '$contactID'");

$contact = mysqli_fetch_array($sql);

echo json_encode($contact);
?>