<?php
session_start();

//connection setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}

$number = $_POST['number'];
$id =  isset($_POST['id']) ? (int) $_POST['id'] : 0;
$sql = mysqli_query($conn, "SELECT * FROM `contacts` WHERE `number` = '$number' AND `id` != '$id' AND `remove` = '0'");

if(mysqli_num_rows($sql) > 0){
    echo "false";
}else{
    echo "true";
}
exit();
?>