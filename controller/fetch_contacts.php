<?php
session_start();

//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$contactID = $_GET['id'];

$sql = mysqli_query($conn, "SELECT * FROM `contacts` WHERE `id` = '$contactID' AND `remove` = '0'");

$contact = mysqli_fetch_array($sql);

header('Content_type: application/json');
echo json_encode($contact);
?>