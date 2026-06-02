<?php
session_start();

//connection_setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}


$contactId = $_POST['id'];
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$number = $_POST['number'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';

$update = mysqli_query($conn, "UPDATE `contacts` SET
                                                `fname` = '$fname',
                                                `lname` = '$lname',
                                                `number` = '$number',
                                                `email` = '$email',
                                                `address` = '$address'
                                            WHERE `id` = '$contactId'");

if($update){
    echo "success";
}else{
    echo "failed";
}

?>