<?php
session_start();
//connection setup
include("config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$contactId = $_POST['id'];

$delete = mysqli_query($conn, "DELETE FROM `contacts` WHERE `id` = '$contactId'");

if($delete){
    echo "success";
}else{
    echo "failed";
}

?>