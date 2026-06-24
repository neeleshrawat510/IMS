<?php
session_start();

//connection_setup
include("../config/connection.php");

if(!isset($_SESSION['user_id'])){
    header("location: index.php");
    exit();
}


$contactId = $_POST['id'] ?? null;

if (!$contactId) {
    echo "missing id";
    exit();
}

$name = trim(mysqli_real_escape_string($conn, $_POST['name']  ??  ''));
$number = trim(mysqli_real_escape_string($conn, $_POST['number']  ??  ''));
$email = trim(mysqli_real_escape_string($conn, $_POST['email']  ??  ''));
$company = trim(mysqli_real_escape_string($conn, $_POST['company']  ??  ''));
$gst = trim(mysqli_real_escape_string($conn, $_POST['gst']  ??  ''));
$address = trim(mysqli_real_escape_string($conn, $_POST['address']  ??  ''));
$todayDate = date('Y-m-d H:i:s'); //set update date & time


$update = mysqli_query($conn, "UPDATE `contacts` SET
                                                `name` = '$name',
                                                `number` = '$number',
                                                `email` = '$email',
                                                `company` = '$company',
                                                `gst` = '$gst',
                                                `address` = '$address',
                                                `updated_at` = '$todayDate'
                                            WHERE `id` = '$contactId'");

if ($update) {
    echo "success";
} else {
    echo "failed";
}

?>