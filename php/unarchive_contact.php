<?php
session_start();
//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$contactId = $_POST['id'];

$unarchive = mysqli_query($conn, "UPDATE `contacts` SET remove = 0 WHERE `id` = '$contactId'");

if($unarchive){
    echo "success";
}else{
    echo "failed";
}

?>